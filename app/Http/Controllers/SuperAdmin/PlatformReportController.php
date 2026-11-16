<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PlatformReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to'   => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $from = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : now()->endOfDay();

        /*
        |--------------------------------------------------------------------------
        | Users Report
        |--------------------------------------------------------------------------
        */

        $usersTableExists = Schema::hasTable('users');

        $users = [
            'total' => 0,
            'new' => 0,
            'by_role' => collect(),
            'by_status' => collect(),
        ];

        $daily = collect();

        if ($usersTableExists) {

            $hasRole = Schema::hasColumn('users', 'role');
            $hasStatus = Schema::hasColumn('users', 'status');
            $hasCreatedAt = Schema::hasColumn('users', 'created_at');

            $users['total'] = DB::table('users')->count();

            if ($hasCreatedAt) {
                $users['new'] = DB::table('users')
                    ->whereBetween('created_at', [$from, $to])
                    ->count();
            }

            if ($hasRole) {
                $users['by_role'] = DB::table('users')
                    ->select('role', DB::raw('COUNT(*) as total'))
                    ->groupBy('role')
                    ->pluck('total', 'role');
            }

            if ($hasStatus) {
                $users['by_status'] = DB::table('users')
                    ->select('status', DB::raw('COUNT(*) as total'))
                    ->groupBy('status')
                    ->pluck('total', 'status');
            }

            /*
            |--------------------------------------------------------------------------
            | Daily Registrations
            |--------------------------------------------------------------------------
            */

            $chartFrom = $from->copy();

            // Chart-এ সর্বোচ্চ ৬০ দিনের ডাটা দেখাবে
            if ($chartFrom->diffInDays($to) > 59) {
                $chartFrom = $to->copy()->subDays(59)->startOfDay();
            }

            $perDay = collect();

            if ($hasCreatedAt) {
                $perDay = DB::table('users')
                    ->whereBetween('created_at', [$chartFrom, $to])
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                    ->groupBy('date')
                    ->pluck('total', 'date');
            }

            $daily = collect(
                CarbonPeriod::create(
                    $chartFrom->toDateString(),
                    $to->toDateString()
                )
            )->map(function ($date) use ($perDay) {

                $dateKey = $date->toDateString();

                return [
                    'date' => $date->format('d M'),
                    'count' => (int) ($perDay[$dateKey] ?? 0),
                ];

            })->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Platform Catalogue
        |--------------------------------------------------------------------------
        */

        $catalogue = [
            'companies' => Schema::hasTable('companies')
                ? DB::table('companies')->count()
                : null,

            'products' => Schema::hasTable('projects')
                ? DB::table('projects')->count()
                : null,
        ];

        /*
        |--------------------------------------------------------------------------
        | Report Summary
        |--------------------------------------------------------------------------
        */

        $report = [
            'from' => $from,
            'to' => $to,
            'generated_at' => now(),
        ];

        return view(
            'super-admin.reports.platform',
            compact(
                'from',
                'to',
                'users',
                'daily',
                'catalogue',
                'report'
            )
        );
    }
}