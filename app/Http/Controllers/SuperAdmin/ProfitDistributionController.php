<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DailyProfitLog;
use App\Models\ShareHolding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ProfitDistributionController extends Controller
{
    public function index()
    {
        $days = DailyProfitLog::selectRaw('profit_date, COUNT(*) as entries, COUNT(DISTINCT user_id) as users, SUM(profit_bdt) as total_bdt')
            ->groupBy('profit_date')->orderByDesc('profit_date')->limit(14)->get();

        return view('super-admin.profit-distribution.index', [
            'days' => $days,
            'holdings' => ShareHolding::where('shares', '>', 0)->count(),
            'yesterday' => now()->subDay()->toDateString(),
            'output' => session('output'),
        ]);
    }

    public function run(Request $request)
    {
        $data = $request->validate(['date' => ['nullable', 'date', 'before:today']]);

        Artisan::call('profit:distribute', array_filter([
            '--date' => $data['date'] ?? null,
            '--dry' => $request->boolean('dry') ? true : null,
        ]));

        return back()->with('output', Artisan::output())->with('success', 'Command finished.');
    }
}