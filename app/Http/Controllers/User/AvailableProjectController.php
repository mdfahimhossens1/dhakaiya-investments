<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AvailableProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $projects = collect([]);

        $categories = [
            'all' => 'All Categories',
            'business' => 'Business',
            'real_estate' => 'Real Estate',
            'technology' => 'Technology',
            'other' => 'Other',
        ];

        $status = $request->query('status', 'all');

        $allowedStatuses = ['all', 'open', 'upcoming', 'closed'];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        $category = $request->query('category', 'all');

        if (!array_key_exists($category, $categories)) {
            $category = 'all';
        }

        return view('user.investment.available-projects', compact(
            'user',
            'projects',
            'categories',
            'status',
            'category'
        ));
    }
}