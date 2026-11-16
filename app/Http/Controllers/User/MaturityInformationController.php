<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaturityInformationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $status = $request->query('status', 'all');

        $allowedStatuses = ['all', 'upcoming', 'matured', 'completed', 'pending'];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        $today = today();

        $all = $user->shareHoldings()
            ->with('project')
            ->get()
            ->map(function ($h) use ($today) {
                $start = $h->project?->start_date;
                $end = $h->project?->end_date;

                if ($h->shares === 0 || $h->shares === '0') {
                    $state = 'completed';          // সব শেয়ার বিক্রি হয়ে গেছে
                } elseif (!$end) {
                    $state = 'pending';            // প্রজেক্টে শেষ তারিখ দেওয়া নেই
                } elseif ($end->copy()->startOfDay()->lte($today)) {
                    $state = 'matured';
                } else {
                    $state = 'upcoming';
                }

                return (object) [
                    'project_name' => $h->project->name ?? 'Removed project',
                    'reference_id' => $h->project->slug ?? '—',
                    'start_date' => $start?->format('d M Y') ?? '—',
                    'maturity_date' => $end?->format('d M Y') ?? '—',
                    'term' => ($start && $end) ? $start->diffInDays($end) . ' days' : null,
                    'status' => $state,
                    'sort' => $end?->timestamp ?? PHP_INT_MAX,
                ];
            })
            ->sortBy('sort')
            ->values();

        $maturities = $status === 'all' ? $all : $all->where('status', $status)->values();

        $summary = [
            'total_records' => $all->count(),
            'upcoming' => $all->where('status', 'upcoming')->count(),
            'matured' => $all->where('status', 'matured')->count(),
            'completed' => $all->where('status', 'completed')->count(),
            'pending' => $all->where('status', 'pending')->count(),
        ];

        return view('user.investment.maturity-information', compact(
            'user',
            'maturities',
            'summary',
            'status'
        ));
    }
}