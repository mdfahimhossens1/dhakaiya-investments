<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Validate Filters
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'search'    => ['nullable', 'string', 'max:150'],
            'type'      => ['nullable', 'in:auth,create,update,delete'],
            'role'      => ['nullable', 'string', 'max:50'],
            'date_from' => ['nullable', 'date'],
            'date_to'   => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2. Detect Available Columns
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasTable('activity_logs')) {
            return view('super-admin.reports.activity-logs', [
                'logs' => ActivityLog::query()->whereRaw('1 = 0')->paginate(25),
                'stats' => [
                    'total' => 0,
                    'today' => 0,
                    'users_today' => 0,
                    'deletes_today' => 0,
                ],
            ]);
        }

        $logColumns = Schema::getColumnListing('activity_logs');
        $userTableExists = Schema::hasTable('users');

        $userColumns = $userTableExists
            ? Schema::getColumnListing('users')
            : [];

        $hasLog = fn (string $column) => in_array($column, $logColumns);
        $hasUser = fn (string $column) => in_array($column, $userColumns);

        /*
        |--------------------------------------------------------------------------
        | 3. Build User Relationship Fields Dynamically
        |--------------------------------------------------------------------------
        */

        $userSelect = ['id'];

        foreach (['name', 'email', 'role'] as $column) {
            if ($hasUser($column)) {
                $userSelect[] = $column;
            }
        }

        $canUseUser = $userTableExists
            && $hasLog('user_id')
            && $hasUser('id');

        /*
        |--------------------------------------------------------------------------
        | 4. Base Query
        |--------------------------------------------------------------------------
        */

        $query = ActivityLog::query();

        if ($canUseUser) {
            $query->with([
                'user' => fn ($q) => $q->select($userSelect),
            ]);
        }

        if ($hasLog('created_at')) {
            $query->orderByDesc('created_at');
        } elseif ($hasLog('id')) {
            $query->orderByDesc('id');
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $searchColumns = [];

            foreach (['description', 'action', 'ip_address'] as $column) {
                if ($hasLog($column)) {
                    $searchColumns[] = $column;
                }
            }

            $query->where(function ($q) use (
                $searchColumns,
                $search,
                $canUseUser,
                $hasUser
            ) {
                foreach ($searchColumns as $index => $column) {
                    $method = $index === 0 ? 'where' : 'orWhere';

                    $q->{$method}(
                        $column,
                        'like',
                        "%{$search}%"
                    );
                }

                if ($canUseUser && $hasUser('name')) {
                    $q->orWhereHas('user', function ($userQuery) use (
                        $search,
                        $hasUser
                    ) {
                        $userQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );

                        if ($hasUser('email')) {
                            $userQuery->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
                            );
                        }
                    });
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Activity Type Filter
        |--------------------------------------------------------------------------
        */

        $type = $request->input('type');

        if ($type === 'auth' && $hasLog('action')) {
            $query->where('action', 'like', 'auth.%');
        }

        if ($type === 'create' && $hasLog('method')) {
            $query->where('method', 'POST');
        }

        if ($type === 'update' && $hasLog('method')) {
            $query->whereIn('method', ['PUT', 'PATCH']);
        }

        if ($type === 'delete' && $hasLog('method')) {
            $query->where('method', 'DELETE');
        }

        /*
        |--------------------------------------------------------------------------
        | 7. Role Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('role')
            && $canUseUser
            && $hasUser('role')
        ) {
            $role = $request->role;

            $query->whereHas('user', function ($q) use ($role) {
                $q->where('role', $role);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | 8. Date Filters
        |--------------------------------------------------------------------------
        */

        if ($hasLog('created_at')) {
            if ($request->filled('date_from')) {
                $query->whereDate(
                    'created_at',
                    '>=',
                    $request->date_from
                );
            }

            if ($request->filled('date_to')) {
                $query->whereDate(
                    'created_at',
                    '<=',
                    $request->date_to
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 9. Paginated Results
        |--------------------------------------------------------------------------
        */

        $logs = $query
            ->paginate(25)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | 10. Statistics
        |--------------------------------------------------------------------------
        */

        $statsQuery = ActivityLog::query();

        $total = (clone $statsQuery)->count();

        $today = 0;
        $usersToday = 0;
        $deletesToday = 0;

        if ($hasLog('created_at')) {
            $todayQuery = ActivityLog::whereDate(
                'created_at',
                now()->toDateString()
            );

            $today = (clone $todayQuery)->count();

            if (
                $hasLog('user_id')
                && $canUseUser
            ) {
                $usersToday = (clone $todayQuery)
                    ->whereNotNull('user_id')
                    ->distinct()
                    ->count('user_id');
            }

            if ($hasLog('method')) {
                $deletesToday = (clone $todayQuery)
                    ->where('method', 'DELETE')
                    ->count();
            }
        }

        $stats = [
            'total' => $total,
            'today' => $today,
            'users_today' => $usersToday,
            'deletes_today' => $deletesToday,
        ];

        /*
        |--------------------------------------------------------------------------
        | 11. Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'super-admin.reports.activity-logs',
            compact('logs', 'stats')
        );
    }
}