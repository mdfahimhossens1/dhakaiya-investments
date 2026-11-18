<?php

namespace App\Http\Controllers\Agent;

use App\Models\AgentPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends BaseAgentController
{
    public function index()
    {
        $agent = $this->agent()->load(['user', 'districts.country', 'paymentMethods']);

        return view('agent.profile', compact('agent'));
    }

    public function update(Request $request)
    {
        $agent = $this->agent();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'phone'    => ['required', 'string', 'max:30', Rule::unique('users', 'phone')->ignore($agent->user_id)],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'bio'      => ['nullable', 'string', 'max:1000'],
        ]);

        $agent->user->update(['name' => $data['name'], 'phone' => $data['phone']]);
        $agent->update([
            'phone'    => $data['phone'],
            'whatsapp' => $data['whatsapp'] ?? null,
            'bio'      => $data['bio'] ?? null,
        ]);

        return back()->with('success', 'Profile updated.');
    }

    public function toggleAvailability()
    {
        $agent = $this->operational();
        $agent->update(['is_available' => ! $agent->is_available]);

        return back()->with('success', $agent->is_available ? 'You are now available.' : 'You are now unavailable. Users cannot find you in the list.');
    }

    public function storePaymentMethod(Request $request)
    {
        $agent = $this->operational();

        $data = $request->validate([
            'method'         => ['required', Rule::in(array_keys(AgentPaymentMethod::METHODS))],
            'account_name'   => ['required', 'string', 'max:120'],
            'account_number' => ['required', 'string', 'max:80'],
            'bank_name'      => ['nullable', 'required_if:method,bank', 'string', 'max:120'],
            'note'           => ['nullable', 'string', 'max:255'],
        ]);

        if ($agent->paymentMethods()->count() >= 5) {
            return back()->withInput()->with('error', 'You can add up to 5 payment methods.');
        }

        $agent->paymentMethods()->create($data + ['is_active' => true]);

        return back()->with('success', 'Payment method added.');
    }

    public function togglePaymentMethod(AgentPaymentMethod $paymentMethod)
    {
        $agent = $this->agent();
        abort_unless($paymentMethod->agent_id === $agent->id, 404);

        $paymentMethod->update(['is_active' => ! $paymentMethod->is_active]);

        return back()->with('success', 'Payment method updated.');
    }

    public function destroyPaymentMethod(AgentPaymentMethod $paymentMethod)
    {
        $agent = $this->agent();
        abort_unless($paymentMethod->agent_id === $agent->id, 404);

        $paymentMethod->delete();

        return back()->with('success', 'Payment method removed.');
    }
}