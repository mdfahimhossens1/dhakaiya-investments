<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class ProjectDetailsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('user.investment.project-details', [
            'user' => $user,
            'project' => null,
        ]);
    }

    public function show(string $slug)
    {
        $user = auth()->user();

        // Project database is not activated yet.
        $project = null;

        return view('user.investment.project-details', compact(
            'user',
            'project',
            'slug'
        ));
    }
}