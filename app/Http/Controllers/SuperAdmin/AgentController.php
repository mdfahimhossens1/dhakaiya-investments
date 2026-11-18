<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentCountry;
use App\Models\AgentDistrict;
use App\Models\AgentPaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $approval = $request->query('approval');

        $agents = Agent::with(['user:id,name,email', 'districts.country'])
            ->withCount('requests')
            ->when($q !== '', fn ($query) => $query->whereHas('user', fn ($u) => $u->where(
                fn ($x) => $x->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")
            )))
            ->when(in_array($approval, ['pending', 'approved', 'rejected'], true),
                fn ($query) => $query->where('approval_status', $approval))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total'    => Agent::count(),
            'pending'  => Agent::where('approval_status', 'pending')->count(),
            'verified' => Agent::where('is_verified', true)->count(),
            'active'   => Agent::where('is_active', true)->where('approval_status', 'approved')->count(),
        ];

        return view('super-admin.agents.index', compact('agents', 'stats', 'q', 'approval'));
    }

    public function create()
    {
        return view('super-admin.agents.create', [
            'agent'     => null,
            'countries' => $this->countries(),
            'selected'  => old('district_ids', []),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(array_merge([
            'name'           => ['required', 'string', 'max:120'],
            'email'          => ['required', 'email', 'max:190', 'unique:users,email'],
            'phone'          => ['required', 'string', 'max:30', 'unique:users,phone'],
            'whatsapp'       => ['nullable', 'string', 'max:30'],
            'bio'            => ['nullable', 'string', 'max:1000'],
            'password'       => ['required', 'string', 'min:8', 'confirmed'],
            'district_ids'   => ['required', 'array', 'min:1'],
            'district_ids.*' => ['integer', 'exists:agent_districts,id'],
            'is_verified'    => ['nullable', 'boolean'],
        ], $this->paymentRules()));

        $agent = DB::transaction(function () use ($data, $request) {
            $districts = AgentDistrict::with('country')->whereIn('id', $data['district_ids'])->get();
            $country = $districts->first()->country;

            $user = new User();
            $user->forceFill([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'phone'             => $data['phone'],
                'country'           => strtoupper($country->code ?: \App\Support\Currency::normalize($country->name)),
                'password'          => $data['password'],
                'role'              => 'agent',
                'status'            => 'active',
                'email_verified_at' => now(),
            ])->save();

            // Checkbox na thakle default verified
            $verified = $request->has('is_verified') ? $request->boolean('is_verified') : true;

            $agent = Agent::create([
                'user_id'         => $user->id,
                'phone'           => $data['phone'],
                'whatsapp'        => $data['whatsapp'] ?? null,
                'bio'             => $data['bio'] ?? null,
                'approval_status' => 'approved',
                'is_verified'     => $verified,
                'verified_at'     => $verified ? now() : null,
                'is_active'       => true,
                'is_available'    => true,
            ]);

            $agent->districts()->sync($this->pivot($districts));
            $this->syncPaymentMethods($agent, $data['payment_methods'] ?? []);

            return $agent;
        });

        return redirect()->route('super-admin.agents.show', $agent)
            ->with('success', 'Agent created.');
    }

    public function show(Agent $agent)
    {
        $agent->load(['user', 'districts.country', 'paymentMethods']);

        $counts = $agent->requests()
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');

        $recent = $agent->requests()->with('user:id,name')->latest()->limit(8)->get();

        return view('super-admin.agents.show', compact('agent', 'counts', 'recent'));
    }

    public function edit(Agent $agent)
    {
        $agent->load(['user', 'districts', 'paymentMethods']);

        return view('super-admin.agents.edit', [
            'agent'     => $agent,
            'countries' => $this->countries(),
            'selected'  => old('district_ids', $agent->districts->pluck('id')->all()),
        ]);
    }

    public function update(Request $request, Agent $agent)
    {
        $user = $agent->user;

        $data = $request->validate(array_merge([
            'name'           => ['required', 'string', 'max:120'],
            'email'          => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'          => ['required', 'string', 'max:30', Rule::unique('users', 'phone')->ignore($user->id)],
            'whatsapp'       => ['nullable', 'string', 'max:30'],
            'bio'            => ['nullable', 'string', 'max:1000'],
            'password'       => ['nullable', 'string', 'min:8', 'confirmed'],
            'district_ids'   => ['required', 'array', 'min:1'],
            'district_ids.*' => ['integer', 'exists:agent_districts,id'],
        ], $this->paymentRules()));

        DB::transaction(function () use ($data, $user, $agent, $request) {
            $districts = AgentDistrict::whereIn('id', $data['district_ids'])->get();

            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'];
            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->save();

            $agent->update([
                'phone'    => $data['phone'],
                'whatsapp' => $data['whatsapp'] ?? null,
                'bio'      => $data['bio'] ?? null,
            ]);

            $agent->districts()->sync($this->pivot($districts));

            if ($request->has('payment_methods')) {
                $this->syncPaymentMethods($agent, $data['payment_methods'] ?? []);
            }
        });

        return redirect()->route('super-admin.agents.show', $agent)->with('success', 'Agent updated.');
    }

    public function toggleVerify(Agent $agent)
    {
        $verified = ! $agent->is_verified;

        $agent->update([
            'is_verified' => $verified,
            'verified_at' => $verified ? now() : null,
        ]);

        return back()->with('success', $verified ? 'Agent verified.' : 'Verification removed.');
    }

    public function approval(Request $request, Agent $agent)
    {
        $data = $request->validate(['approval_status' => ['required', 'in:pending,approved,rejected']]);

        $agent->update($data);

        return back()->with('success', 'Approval status: ' . ucfirst($data['approval_status']) . '.');
    }

    public function toggleStatus(Agent $agent)
    {
        $agent->update(['is_active' => ! $agent->is_active]);

        return back()->with('success', $agent->is_active ? 'Agent activated.' : 'Agent deactivated.');
    }

    private function countries()
    {
        return AgentCountry::with(['districts' => fn ($q) => $q->orderBy('name')])->orderBy('name')->get();
    }

    private function pivot($districts): array
    {
        return $districts->mapWithKeys(fn ($d) => [
            $d->id => ['agent_country_id' => $d->agent_country_id],
        ])->all();
    }

    private function paymentRules(): array
    {
        return [
            'payment_methods'                  => ['nullable', 'array', 'max:5'],
            'payment_methods.*.id'             => ['nullable', 'integer'],
            'payment_methods.*.method'         => ['nullable', Rule::in(array_keys(AgentPaymentMethod::METHODS))],
            'payment_methods.*.account_name'   => ['nullable', 'string', 'max:120'],
            'payment_methods.*.account_number' => ['nullable', 'string', 'max:80'],
            'payment_methods.*.bank_name'      => ['nullable', 'string', 'max:120'],
        ];
    }

    /**
     * Existing row update hoy (id thik thake, research request er link bhange na).
     * Form theke remove kora row delete hoy. Faka row skip hoy.
     */
    private function syncPaymentMethods(Agent $agent, array $methods): void
    {
        $keep = [];

        foreach ($methods as $m) {
            if (empty($m['method']) || empty($m['account_number'])) {
                continue;
            }

            $attrs = [
                'method'         => $m['method'],
                'account_name'   => $m['account_name'] ?: $agent->user->name,
                'account_number' => $m['account_number'],
                'bank_name'      => $m['method'] === 'bank' ? ($m['bank_name'] ?? null) : null,
            ];

            $row = ! empty($m['id']) ? $agent->paymentMethods()->find($m['id']) : null;

            if ($row) {
                $row->update($attrs);
            } else {
                $row = $agent->paymentMethods()->create($attrs + ['is_active' => true]);
            }

            $keep[] = $row->id;
        }

        $agent->paymentMethods()->whereNotIn('id', $keep)->delete();
    }
}