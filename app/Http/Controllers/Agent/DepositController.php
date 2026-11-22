<?php

namespace App\Http\Controllers\Agent;

use App\Models\AgentDeposit;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepositController extends BaseAgentController
{
    public function index(Request $request)
    {
        $agent = $this->agent();
        $status = $request->query('status');

        $deposits = $agent->deposits()
            ->when(
                in_array($status, ['pending', 'approved', 'rejected'], true),
                fn ($q) => $q->where('status', $status)
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('agent.deposits.index', compact('agent', 'deposits', 'status'));
    }

    public function create()
    {
        return view('agent.deposits.create', [
            'agent' => $this->agent(),
            'methods' => PaymentMethod::orderBy('name')->get(['code', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $agent = $this->agent();

        if (! $agent->isOperational()) {
            return back()->with('error', 'আপনার এজেন্ট অ্যাকাউন্ট এখন সক্রিয় নয়।');
        }

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0', 'max:1000000', 'decimal:0,2'],
            'method' => ['required', Rule::in(PaymentMethod::pluck('code')->all())],
            'transaction_id' => ['required', 'string', 'max:100'],
            'sender_info' => ['nullable', 'string', 'max:150'],
        ]);

        // একই Transaction ID দুবার ব্যবহার আটকানো (rejected বাদে)
        $used = AgentDeposit::where('method', $data['method'])
            ->where('transaction_id', $data['transaction_id'])
            ->where('status', '!=', 'rejected')
            ->exists();

        if ($used) {
            return back()->withInput()->withErrors([
                'transaction_id' => 'এই Transaction ID আগেই ব্যবহার করা হয়েছে।',
            ]);
        }

        // স্প্যাম আটকাতে: একসাথে সর্বোচ্চ ৫টা pending
        if ($agent->deposits()->where('status', 'pending')->count() >= 5) {
            return back()->withInput()->with('error', 'আপনার ৫টা ডিপোজিট রিকোয়েস্ট এখনো pending। আগে সেগুলো প্রসেস হতে দিন।');
        }

        $agent->deposits()->create($data + ['status' => 'pending']);

        return redirect()->route('agent.deposits.index')
            ->with('success', 'Deposit request পাঠানো হয়েছে। Super Admin যাচাই করে Approve করলে ব্যালেন্সে যোগ হবে।');
    }
}