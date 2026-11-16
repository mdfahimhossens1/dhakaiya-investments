<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\User;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user:id,name,email', 'assignee:id,name'])
            ->orderByRaw('COALESCE(last_reply_at, created_at) DESC');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('ticket_no', 'like', "%{$s}%")
                  ->orWhere('subject', 'like', "%{$s}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"));
            });
        }
        foreach (['status', 'priority', 'category'] as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->input($filter));
            }
        }

        $tickets = $query->paginate(15)->withQueryString();

        $counts = SupportTicket::select('status', DB::raw('count(*) as total'))->groupBy('status')->pluck('total', 'status');
        $stats = [
            'total'       => $counts->sum(),
            'open'        => $counts['open'] ?? 0,
            'in_progress' => $counts['in_progress'] ?? 0,
            'answered'    => $counts['answered'] ?? 0,
            'done'        => ($counts['resolved'] ?? 0) + ($counts['closed'] ?? 0),
        ];

        return view('super-admin.support-tickets.index', compact('tickets', 'stats'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['user:id,name,email,role', 'assignee:id,name', 'replies.user:id,name,role']);
        $admins = User::whereIn('role', ['admin', 'super_admin'])->orderBy('name')->get(['id', 'name']);

        return view('super-admin.support-tickets.show', compact('ticket', 'admins'));
    }

    /** Change status / priority / assignee. */
    public function update(Request $request, SupportTicket $ticket)
    {
        $data = $request->validate([
            'status'      => 'required|in:' . implode(',', array_keys(SupportTicket::STATUSES)),
            'priority'    => 'required|in:' . implode(',', array_keys(SupportTicket::PRIORITIES)),
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $data['closed_at'] = $data['status'] === 'closed' ? ($ticket->closed_at ?? now()) : null;

        $ticket->update($data);

        return back()->with('success', 'Ticket updated.');
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $data = $request->validate([
            'message' => 'required|string|max:5000',
            'status'  => 'nullable|in:' . implode(',', array_keys(SupportTicket::STATUSES)),
        ]);

        $status = $data['status'] ?? 'answered';

        DB::transaction(function () use ($ticket, $data, $status) {
            $ticket->replies()->create([
                'user_id'  => auth()->id(),
                'message'  => $data['message'],
                'is_admin' => true,
            ]);

            $ticket->update([
                'status'        => $status,
                'last_reply_at' => now(),
                'closed_at'     => $status === 'closed' ? now() : null,
            ]);
        });

        AdminNotification::trySend(
            $ticket->user,
            "Support reply: {$ticket->ticket_no}",
            Str::limit($data['message'], 140),
            'info',
            route('user.support.show-ticket', $ticket)
        );

        return back()->with('success', 'Reply sent to the user.');
    }

    public function destroy(SupportTicket $ticket)
    {
        $ticket->delete();

        return redirect()->route('super-admin.support-tickets.index')->with('success', 'Ticket deleted.');
    }
}
