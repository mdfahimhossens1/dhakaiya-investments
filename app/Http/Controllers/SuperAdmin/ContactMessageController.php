<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::latest('id');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('subject', 'like', "%{$s}%")
                  ->orWhere('message', 'like', "%{$s}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->paginate(15)->withQueryString();

        $counts = ContactMessage::select('status', DB::raw('count(*) as total'))->groupBy('status')->pluck('total', 'status');
        $stats = [
            'total'    => $counts->sum(),
            'new'      => $counts['new'] ?? 0,
            'read'     => $counts['read'] ?? 0,
            'replied'  => $counts['replied'] ?? 0,
        ];

        return view('super-admin.contact-messages.index', compact('messages', 'stats'));
    }

    /** Opening a message marks it as read. */
    public function show(ContactMessage $contactMessage)
    {
        if ($contactMessage->status === 'new') {
            $contactMessage->update(['status' => 'read', 'read_at' => now()]);
        }

        $contactMessage->load(['user:id,name,email,role', 'repliedBy:id,name']);

        return view('super-admin.contact-messages.show', ['contact' => $contactMessage]);
    }

    public function update(Request $request, ContactMessage $contactMessage)
    {
        $data = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(ContactMessage::STATUSES)),
        ]);

        $contactMessage->update($data);

        return back()->with('success', 'Message status updated.');
    }

    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $data = $request->validate(['admin_reply' => 'required|string|max:3000']);

        $contactMessage->update([
            'admin_reply' => $data['admin_reply'],
            'status'      => 'replied',
            'replied_by'  => auth()->id(),
            'replied_at'  => now(),
            'read_at'     => $contactMessage->read_at ?? now(),
        ]);

        AdminNotification::trySend(
            $contactMessage->user,
            'Reply to your message: ' . Str::limit($contactMessage->subject, 60),
            Str::limit($data['admin_reply'], 140),
            'info',
            route('user.support.contact')
        );

        return back()->with('success', 'Reply saved' . ($contactMessage->user_id ? ' and the user was notified.' : '.'));
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('super-admin.contact-messages.index')->with('success', 'Message deleted.');
    }
}
