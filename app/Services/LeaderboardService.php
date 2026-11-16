<?php

namespace App\Services;

use App\Models\ShareOrder;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LeaderboardService
{
    /** রিসেল Leaderboard (মোট রিসেল টাকার ভিত্তিতে) */
    public function resellTop(?Carbon $from, ?Carbon $to, int $limit = 10): Collection
    {
        $wash = max(0, (int) config('rewards.resell.wash_days', 7));

        return ShareOrder::query()
            ->join('users', 'users.id', '=', 'share_orders.user_id')
            ->where('share_orders.type', 'sell')
            ->where('share_orders.status', 'completed')
            ->where('users.status', 'active')
            ->where('users.role', 'user')
            ->when($from, fn ($q) => $q->where('share_orders.created_at', '>=', $from))
            ->when($to, fn ($q) => $q->where('share_orders.created_at', '<=', $to))
            // কেনার পর অল্প সময়ে রিসেল (কিনে-বেচে র‍্যাংক বাড়ানো) গোনা হবে না
            ->when($wash > 0, fn ($q) => $q->whereNotExists(function ($b) use ($wash) {
                $b->selectRaw('1')
                    ->from('share_orders as buys')
                    ->whereColumn('buys.user_id', 'share_orders.user_id')
                    ->whereColumn('buys.project_id', 'share_orders.project_id')
                    ->where('buys.type', 'buy')
                    ->whereColumn('buys.created_at', '<=', 'share_orders.created_at')
                    ->whereRaw('buys.created_at >= DATE_SUB(share_orders.created_at, INTERVAL ? DAY)', [$wash]);
            }))
            ->groupBy('share_orders.user_id', 'users.name')
            ->selectRaw('share_orders.user_id, users.name, SUM(share_orders.shares) as shares, SUM(share_orders.net_bdt) as volume, COUNT(*) as trades')
            ->orderByDesc('volume')
            ->orderBy('share_orders.user_id')
            ->limit($limit)
            ->get();
    }

    /** অন্যের নাম আংশিক ঢেকে দেখানো */
    public function mask(string $name): string
    {
        $name = trim($name);

        if (mb_strlen($name) <= 2) {
            return $name . '***';
        }

        return mb_substr($name, 0, 2) . str_repeat('*', min(5, mb_strlen($name) - 2));
    }
}