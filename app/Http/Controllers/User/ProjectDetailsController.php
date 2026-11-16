<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\PositionService;
use App\Services\ReferralService;
use App\Services\ShareException;
use App\Services\ShareService;
use Illuminate\Http\Request;

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

    public function show(string $slug, ShareService $shares)
    {
        $user = auth()->user();

        $project = Project::with(['company', 'projectCategory', 'projectTemplate'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();

        $holding = null;
        $walletOptions = [];
        $canBuy = false;

        if ($project) {
            $holding = $user->shareHoldings()->where('project_id', $project->id)->first();

            // ইউজারের Wallet-গুলোর মধ্যে যেগুলোর exchange rate সেট আছে
            foreach ($user->wallets()->get() as $wallet) {
                $unit = $shares->unitPriceIn($project, $wallet->currency);

                if ($unit === null) {
                    continue;
                }

                $walletOptions[] = [
                    'currency' => $wallet->currency,
                    'balance' => $wallet->balance,
                    'rate' => $shares->rate($wallet->currency),
                    'unit_price' => $unit,
                ];
            }

            $canBuy = $project->isOpenForPurchase();
        }

        return view('user.investment.project-details', compact(
            'user',
            'project',
            'slug',
            'holding',
            'walletOptions',
            'canBuy'
        ));
    }

    public function buy(Request $request, string $slug, ShareService $shares)
    {
        $data = $request->validate([
            'shares' => ['required', 'integer', 'min:1', 'max:1000000'],
            'currency' => ['required', 'string', 'size:3'],
        ]);

        $project = Project::where('slug', $slug)->where('is_active', true)->firstOrFail();

        try {
            $order = $shares->buy(
                $request->user(),
                $project,
                (int) $data['shares'],
                $data['currency']
            );
        } catch (ShareException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        // ---- কেনা সফল। নিচের কাজগুলো ব্যর্থ হলেও কেনা বাতিল হবে না ----

        // ১) রেফারেল কমিশন (শেয়ার কেনার উপর)
        try {
            app(ReferralService::class)->onSharePurchase($order);
        } catch (\Throwable $e) {
            report($e);
        }

        // ২) Auto-lock (.env: POSITION_AUTO_LOCK=true হলে)
        if (config('rewards.position.auto_lock_on_buy')) {
            try {
                app(PositionService::class)->lock($request->user(), $project, $order->shares);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()
            ->route('user.purchase-history.index')
            ->with('success', "{$order->shares}টি শেয়ার সফলভাবে কেনা হয়েছে।");
    }
}