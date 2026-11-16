<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProfileReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProfileReviewController extends Controller
{
    /**
     * Display KYC / Profile Review Dashboard
     */
    public function index(Request $request)
    {
        // Validate filters
        $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'pending',
                    'under_review',
                    'reviewed',
                    'needs_update',
                ]),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Users Query
        |--------------------------------------------------------------------------
        */

        $query = User::query()
            ->where('role', 'user')
            ->with([
                'profileReview.reviewer:id,name',
            ]);

        // Search by name, phone and email
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Review Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $status = $request->status;

            if ($status === 'pending') {

                $query->where(function ($q) {

                    $q->whereDoesntHave('profileReview')

                        ->orWhereHas('profileReview', function ($review) {

                            $review->where('status', 'pending');

                        });

                });

            } else {

                $query->whereHas('profileReview', function ($review) use ($status) {

                    $review->where('status', $status);

                });

            }
        }

        /*
        |--------------------------------------------------------------------------
        | Paginated Users
        |--------------------------------------------------------------------------
        */

        $users = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        $totalUsers = User::where('role', 'user')
            ->count();

        // Pending users include users who have no review record
        $pendingUsers = User::where('role', 'user')
            ->where(function ($q) {

                $q->whereDoesntHave('profileReview')

                    ->orWhereHas('profileReview', function ($review) {

                        $review->where('status', 'pending');

                    });

            })
            ->count();

        // Under Review
        $underReviewUsers = ProfileReview::where('status', 'under_review')
            ->whereHas('user', function ($q) {

                $q->where('role', 'user');

            })
            ->count();

        // Reviewed
        $reviewedUsers = ProfileReview::where('status', 'reviewed')
            ->whereHas('user', function ($q) {

                $q->where('role', 'user');

            })
            ->count();

        // Needs Update
        $needsUpdateUsers = ProfileReview::where('status', 'needs_update')
            ->whereHas('user', function ($q) {

                $q->where('role', 'user');

            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'super-admin.profile-reviews.index',
            compact(
                'users',
                'totalUsers',
                'pendingUsers',
                'underReviewUsers',
                'reviewedUsers',
                'needsUpdateUsers'
            )
        );
    }


    /**
     * Show User Profile Details
     *
     * Used by AJAX Modal
     */
    public function show(User $user)
    {
        // Only normal users can be reviewed
        abort_unless($user->role === 'user', 404);

        $user->load([
            'profileReview.reviewer:id,name',
        ]);

        $review = $user->profileReview;

        return response()->json([

            'user' => [

                'id' => $user->id,

                'name' => $user->name,

                'email' => $user->email,

                'phone' => $user->phone,

                'country' => $user->country,

                'status' => $user->status,

                'created_at' => $user->created_at
                    ? $user->created_at->format('d M Y, h:i A')
                    : null,

            ],

            'review' => $review ? [

                'status' => $review->status,

                'review_notes' => $review->review_notes,

                'reviewed_at' => $review->reviewed_at
                    ? $review->reviewed_at->format('d M Y, h:i A')
                    : null,

                'reviewer' => $review->reviewer?->name,

            ] : null,

        ]);
    }


    /**
     * Update User Profile Review
     */
    public function update(Request $request, User $user)
    {
        // Only normal users can be reviewed
        abort_unless($user->role === 'user', 404);

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'under_review',
                    'reviewed',
                    'needs_update',
                ]),
            ],

            'review_notes' => [
                'nullable',
                'string',
                'max:2000',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Save Review
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated, $user) {

            $review = ProfileReview::firstOrNew([
                'user_id' => $user->id,
            ]);

            $review->status = $validated['status'];

            $review->review_notes = $validated['review_notes'] ?? null;

            if ($validated['status'] === 'pending') {

                $review->reviewed_by = null;

                $review->reviewed_at = null;

            } else {

                $review->reviewed_by = auth()->id();

                $review->reviewed_at = now();

            }

            $review->save();

        });

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('super-admin.profile-reviews.index')
            ->with('success', 'Profile review updated successfully.');
    }
}