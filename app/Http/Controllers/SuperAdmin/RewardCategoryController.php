<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\RewardCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RewardCategoryController extends Controller
{
    public function index()
    {
        $categories = RewardCategory::orderBy('sort_order')->orderBy('id')->paginate(15);

        return view('super-admin.reward-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);

        RewardCategory::create($data);

        return back()->with('success', 'Reward category created.');
    }

    public function show(RewardCategory $rewardCategory)
    {
        return response()->json($rewardCategory);
    }

    public function update(Request $request, RewardCategory $rewardCategory)
    {
        $rewardCategory->update($this->validated($request));

        return back()->with('success', 'Reward category updated.');
    }

    public function destroy(RewardCategory $rewardCategory)
    {
        $rewardCategory->delete();

        return back()->with('success', 'Reward category deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:500',
            'reward_type' => 'required|in:cash,bonus_share,gift,badge',
            'value_type' => 'required|in:fixed,percent',
            'reward_value' => 'required|numeric|min:0',
            'min_investment' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // checkbox: tick করা থাকলে Active, না থাকলে Inactive
        $data['status'] = $request->boolean('status');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}