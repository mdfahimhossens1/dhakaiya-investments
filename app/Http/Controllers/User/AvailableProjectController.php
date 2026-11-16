<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;

class AvailableProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Category এখন ডেটাবেস থেকে (slug => name)
        $categories = ['all' => 'All Categories'] + ProjectCategory::where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name', 'slug')
            ->all();

        $status = $request->query('status', 'all');

        $allowedStatuses = ['all', 'open', 'upcoming', 'closed'];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        $category = (string) $request->query('category', 'all');

        if (!array_key_exists($category, $categories)) {
            $category = 'all';
        }

        $projects = Project::with(['company', 'projectCategory', 'projectTemplate'])
            ->where('is_active', true)
            ->when($category !== 'all', fn ($q) => $q->whereHas(
                'projectCategory',
                fn ($c) => $c->where('slug', $category)
            ))
            ->when($status === 'open', fn ($q) => $q
                ->where('status', 'active')
                ->where('available_shares', '>', 0))
            ->when($status === 'upcoming', fn ($q) => $q->where('status', 'upcoming'))
            ->when($status === 'closed', fn ($q) => $q->where(function ($w) {
                $w->whereIn('status', ['completed', 'paused'])
                    ->orWhere(fn ($x) => $x->where('status', 'active')->where('available_shares', 0));
            }))
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%' . $request->query('search') . '%'))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // ইউজার কোন প্রজেক্টে কয়টা শেয়ার ধরে আছে (project_id => shares)
        $myHoldings = $user->shareHoldings()
            ->where('shares', '>', 0)
            ->pluck('shares', 'project_id');

        return view('user.investment.available-projects', compact(
            'user',
            'projects',
            'categories',
            'status',
            'category',
            'myHoldings'
        ));
    }
}