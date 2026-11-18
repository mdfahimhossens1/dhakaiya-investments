<?php

namespace App\Http\Controllers\Agent;

use App\Models\AgentMessage;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends BaseAgentController
{
    public function index()
    {
        $agent = $this->agent();

        $threads = AgentMessage::where('agent_id', $agent->id)
            ->selectRaw(
                'user_id, MAX(id) as last_id, SUM(CASE WHEN sender_id != ? AND read_at IS NULL THEN 1 ELSE 0 END) as unread',
                [$agent->user_id]
            )
            ->groupBy('user_id')
            ->orderByDesc('last_id')
            ->get();

        $users = User::whereIn('id', $threads->pluck('user_id'))->get(['id', 'name'])->keyBy('id');
        $last = AgentMessage::whereIn('id', $threads->pluck('last_id'))->get()->keyBy('id');

        return view('agent.messages.index', compact('threads', 'users', 'last'));
    }

    public function show(User $user)
    {
        $agent = $this->agent();

        abort_unless(AgentMessage::where('agent_id', $agent->id)->where('user_id', $user->id)->exists(), 404);

        AgentMessage::where('agent_id', $agent->id)
            ->where('user_id', $user->id)
            ->where('sender_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = AgentMessage::where('agent_id', $agent->id)
            ->where('user_id', $user->id)
            ->latest('id')->limit(200)->get()->reverse()->values();

        $requests = $agent->requests()->where('user_id', $user->id)->latest()->limit(10)->get();

        return view('agent.messages.show', compact('agent', 'user', 'messages', 'requests'));
    }

    public function store(Request $request, User $user)
    {
        $agent = $this->operational();

        abort_unless(AgentMessage::where('agent_id', $agent->id)->where('user_id', $user->id)->exists(), 404);

        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);

        AgentMessage::create([
            'agent_id'  => $agent->id,
            'user_id'   => $user->id,
            'sender_id' => $agent->user_id,
            'body'      => $data['body'],
        ]);

        return back();
    }
}