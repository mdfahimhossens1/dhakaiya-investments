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

        $allowedStatuses = [
            'all',
            'pending',
            'active',
            'completed',
            'rejected',
        ];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        $projects = collect([]);

        $summary = [
            'total_projects' => 0,
            'total_shares' => 0,
            'total_amount' => 0,
            'active_projects' => 0,
            'pending_projects' => 0,
            'completed_projects' => 0,
        ];

        return view('user.investment.project-wise-records', compact(
            'user',
            'projects',
            'summary',
            'status'
        ));
    }
}