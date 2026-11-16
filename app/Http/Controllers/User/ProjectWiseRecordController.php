<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectWiseRecordController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $status = $request->query('status', 'all');

        $allowedStatuses = ['all', 'pending', 'active', 'completed', 'rejected'];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        // শেয়ার ধরে আছে (active) বা সব বিক্রি করে ফেলেছে (completed)
        $holdingRows = $user->shareHoldings()
            ->with('project.company', 'project.projectCategory')
            ->get()
            ->map(fn ($h) => (object) [
                'name' => $h->project->name ?? 'Removed project',
                'reference' => $h->project->company->name ?? null,
                'category' => $h->project->projectCategory->name ?? null,
                'investment_date' => $h->created_at?->format('d M Y'),
                'quantity' => (int) $h->shares,
                'amount' => (float) $h->invested_bdt,
                'status' => $h->shares > 0 ? 'active' : 'completed',
            ]);

        // এখনো অপেক্ষমাণ বা বাতিল হওয়া কেনার অর্ডার
        $orderRows = $user->shareOrders()
            ->where('type', 'buy')
            ->whereIn('status', ['pending', 'rejected'])
            ->with('project.company', 'project.projectCategory')
            ->latest()
            ->get()
            ->map(fn ($o) => (object) [
                'name' => $o->project->name ?? 'Removed project',
                'reference' => $o->project->company->name ?? null,
                'category' => $o->project->projectCategory->name ?? null,
                'investment_date' => $o->created_at->format('d M Y'),
                'quantity' => (int) $o->shares,
                'amount' => (float) $o->net_bdt,
                'status' => $o->status,
            ]);

        $all = $holdingRows->concat($orderRows)->values();

        $projects = $status === 'all' ? $all : $all->where('status', $status)->values();

        $active = $all->where('status', 'active');

        $summary = [
            'total_projects' => $active->count(),
            'total_shares' => $active->sum('quantity'),
            'total_amount' => $active->sum('amount'),
            'active_projects' => $active->count(),
            'pending_projects' => $all->where('status', 'pending')->count(),
            'completed_projects' => $all->where('status', 'completed')->count(),
        ];

        return view('user.investment.project-wise-records', compact(
            'user',
            'projects',
            'summary',
            'status'
        ));
    }
}