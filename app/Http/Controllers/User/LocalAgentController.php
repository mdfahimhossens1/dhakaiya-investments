<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentCountry;
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

        // Query-তে country না থাকলে ইউজারের নিজের দেশ ডিফল্ট
        $countryId = (int) $request->query('country');
        if ($countryId === 0 && ! $request->has('country')) {
            $countryId = (int) optional($this->userCountry($countries, $user))->id;
        }
        $districtId = (int) $request->query('district');
        $searched = $countryId > 0;

        $agents = $searched
            ? $this->agentsQuery($countryId, $districtId)->paginate(12)->withQueryString()
            : null;

        return view('user.agents.index', compact('geo', 'countryId', 'districtId', 'searched', 'agents'));
    }

    /**
     * Deposit পেজের জন্য ইউজারের দেশের এজেন্ট তালিকা।
     * কোনো error হলে খালি collection দেয়, যাতে Deposit পেজ কখনো না ভাঙে।
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

    private function agentsQuery(int $countryId, int $districtId)
    {
        return Agent::listed()
            ->with([
                'user:id,name',
                'districts.country',
                'paymentMethods' => fn ($q) => $q->where('is_active', true),
            ])
            ->whereHas('locations', fn ($q) => $q
                ->where('agent_country_id', $countryId)
                ->when($districtId, fn ($x) => $x->where('agent_district_id', $districtId)))
            ->orderByDesc('is_available')
            ->orderBy('id');
    }

    /** User->country (কোড বা নাম) থেকে AgentCountry খুঁজে বের করে */
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