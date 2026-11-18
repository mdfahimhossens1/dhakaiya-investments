<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AgentCountry;
use App\Models\AgentDistrict;
use App\Models\AgentLocation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AgentLocationController extends Controller
{
    public function index()
    {
        $countries = AgentCountry::with([
            'districts' => fn ($q) => $q->withCount('locations')->orderBy('name'),
        ])->orderBy('name')->get();

        return view('super-admin.agent-locations.index', compact('countries'));
    }

    public function storeCountry(Request $request)
    {
        $request->merge(['code' => strtoupper(trim((string) $request->input('code')))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:agent_countries,name'],
            'code' => ['nullable', 'string', 'max:3'],
        ]);

        AgentCountry::create($data + ['is_active' => true]);

        return back()->with('success', 'Country added.');
    }

    public function updateCountry(Request $request, AgentCountry $country)
    {
        $request->merge(['code' => strtoupper(trim((string) $request->input('code')))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('agent_countries', 'name')->ignore($country->id)],
            'code' => ['nullable', 'string', 'max:3'],
        ]);

        $country->update($data + ['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'Country updated.');
    }

    public function destroyCountry(AgentCountry $country)
    {
        if (AgentLocation::where('agent_country_id', $country->id)->exists()) {
            return back()->with('error', 'Agents are assigned to this country. Remove them first.');
        }

        $country->districts()->delete();
        $country->delete();

        return back()->with('success', 'Country deleted.');
    }

    public function storeDistrict(Request $request)
    {
        $data = $request->validate([
            'agent_country_id' => ['required', 'exists:agent_countries,id'],
            'name'             => [
                'required', 'string', 'max:100',
                Rule::unique('agent_districts', 'name')->where('agent_country_id', $request->input('agent_country_id')),
            ],
        ]);

        AgentDistrict::create($data + ['is_active' => true]);

        return back()->with('success', 'District added.');
    }

    public function updateDistrict(Request $request, AgentDistrict $district)
    {
        $data = $request->validate([
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('agent_districts', 'name')
                    ->where('agent_country_id', $district->agent_country_id)
                    ->ignore($district->id),
            ],
        ]);

        $district->update($data + ['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'District updated.');
    }

    public function destroyDistrict(AgentDistrict $district)
    {
        if ($district->locations()->exists()) {
            return back()->with('error', 'Agents are assigned to this district. Remove them first.');
        }

        $district->delete();

        return back()->with('success', 'District deleted.');
    }
}