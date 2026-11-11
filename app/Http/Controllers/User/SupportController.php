<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Submit Ticket Page
     */
    public function submitTicket()
    {
        return view('user.support.submit-ticket');
    }

    /**
     * My Tickets Page
     */
    public function myTickets()
    {
        $tickets = collect([]);

        $summary = [
            'total' => 0,
            'open' => 0,
            'in_progress' => 0,
            'closed' => 0,
        ];

        return view('user.support.my-tickets', compact(
            'tickets',
            'summary'
        ));
    }

    /**
     * Contact Support Page
     */
    public function contact()
    {
        $supportChannels = [
            [
                'name' => 'Email Support',
                'description' => 'Contact the support team through official email.',
                'icon' => 'fa-envelope',
                'status' => 'Not Available',
            ],
            [
                'name' => 'Live Chat',
                'description' => 'Get assistance through live chat.',
                'icon' => 'fa-comments',
                'status' => 'Coming Soon',
            ],
            [
                'name' => 'Help Center',
                'description' => 'Find helpful information and answers.',
                'icon' => 'fa-circle-question',
                'status' => 'Coming Soon',
            ],
        ];

        return view('user.support.contact', compact(
            'supportChannels'
        ));
    }
}