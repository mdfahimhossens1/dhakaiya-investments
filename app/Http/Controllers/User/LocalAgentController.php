<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentCountry;
use App\Models\AgentMessage;
use Illuminate\Http\Request;

class LocalAgentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $countries = AgentCountry::where('is_active', true)
            ->with(['districts' => fn ($q) => $q->where('is_active', true)->orderBy('name')])
            ->orderBy('name')->get();

        $geo = $countries->map(fn ($c) => [
            'id'        => $c->id,
            'name'      => $c->name,
            'districts' => $c->districts->map(fn ($d) => ['id' => $d->id, 'name' => $d->name])->values(),
        ])->values();

        // Query-te country na thakle user er nijer desh default
        $countryId = (int) $request->query('country');
        if ($countryId === 0 && ! $request->has('country')) {
            $countryId = (int) optional($this->userCountry($countries, $user))->id;
        }
        $districtId = (int) $request->query('district');
        $searched = $countryId > 0;

        $agents = $searched
            ? $this->agentsQuery($countryId, $districtId)->paginate(12)->withQueryString()
            : null;

        $threads = AgentMessage::where('user_id', $user->id)
            ->selectRaw(
                'agent_id, MAX(id) as last_id, SUM(CASE WHEN sender_id != ? AND read_at IS NULL THEN 1 ELSE 0 END) as unread',
                [$user->id]
            )
            ->groupBy('agent_id')->orderByDesc('last_id')->limit(10)->get();

        $threadAgents = Agent::with('user:id,name')
            ->whereIn('id', $threads->pluck('agent_id'))
            ->get()->keyBy('id');

        return view('user.agents.index', compact(
            'geo', 'countryId', 'districtId', 'searched', 'agents', 'threads', 'threadAgents'
        ));
    }

    /**
     * Deposit page-er jonno user-er desher agent list.
     * Kono error hole khali collection dey, jate deposit page kokhono na bhange.
     */
    public function forDeposit($user, int $limit = 6)
    {
        try {
            if (! $user) {
                return collect();
            }

            $countries = AgentCountry::where('is_active', true)->get();
            $country = $this->userCountry($countries, $user);

            if (! $country) {
                return collect();
            }

            return $this->agentsQuery($country->id, 0)->limit($limit)->get();
        } catch (\Throwable $e) {
            report($e);

            return collect();
        }
    }

    public function messages(Request $request, Agent $agent)
    {
        $user = $request->user();
        abort_unless($agent->isOperational(), 404);

        AgentMessage::where('agent_id', $agent->id)
            ->where('user_id', $user->id)
            ->where('sender_id', $agent->user_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = AgentMessage::where('agent_id', $agent->id)
            ->where('user_id', $user->id)
            ->latest('id')->limit(200)->get()->reverse()->values();

        $agent->load(['user:id,name', 'districts.country', 'paymentMethods']);
        $requests = $user->researchRequests()->where('agent_id', $agent->id)->latest()->limit(10)->get();

        return view('user.agents.messages', compact('agent', 'messages', 'requests'));
    }

    public function sendMessage(Request $request, Agent $agent)
    {
        abort_unless($agent->isOperational(), 404);

        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);

        AgentMessage::create([
            'agent_id'  => $agent->id,
            'user_id'   => $request->user()->id,
            'sender_id' => $request->user()->id,
            'body'      => $data['body'],
        ]);

        return back();
    }

    private function agentsQuery(int $countryId, int $districtId)
    {
        return Agent::listed()
            ->with(['user:id,name', 'districts.country'])
            ->whereHas('locations', fn ($q) => $q
                ->where('agent_country_id', $countryId)
                ->when($districtId, fn ($x) => $x->where('agent_district_id', $districtId)))
            ->orderByDesc('is_available')
            ->orderBy('id');
    }

    /** User->country (code ba naam) theke AgentCountry khuje ber kore. */
    private function userCountry($countries, $user): ?AgentCountry
    {
        $value = strtoupper(trim((string) $user->country));
        if ($value === '') {
            return null;
        }

        return $countries->first(fn ($c) =>
            strtoupper((string) $c->code) === $value || strtoupper($c->name) === $value
        );
    }
}