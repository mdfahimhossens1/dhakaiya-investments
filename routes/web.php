<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\AdminController;
use App\Http\Controllers\User\AccountSummaryController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\PersonalInformationController;
use App\Http\Controllers\User\CountryInformationController;
use App\Http\Controllers\User\PaymentInformationController;
use App\Http\Controllers\User\ChangePasswordController;
use App\Http\Controllers\User\KycVerificationController;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\BdtWalletController;
use App\Http\Controllers\User\UsdWalletController;
use App\Http\Controllers\User\DepositHistoryController;
use App\Http\Controllers\User\WithdrawalHistoryController;
use App\Http\Controllers\User\TransactionHistoryController;
use App\Http\Controllers\User\AvailableProjectController;
use App\Http\Controllers\User\ProjectDetailsController;
use App\Http\Controllers\User\MySharesController;
use App\Http\Controllers\User\PurchaseHistoryController;
use App\Http\Controllers\User\SalesHistoryController;
use App\Http\Controllers\User\ShareTransferController;
use App\Http\Controllers\User\InvestmentSummaryController;
use App\Http\Controllers\User\ProjectWiseRecordController;
use App\Http\Controllers\User\ProfitLossHistoryController;
use App\Http\Controllers\User\MaturityInformationController;
use App\Http\Controllers\User\RewardController;
use App\Http\Controllers\User\RewardCategoryController;
use App\Http\Controllers\User\RewardHistoryController;
use App\Http\Controllers\User\AchievementRewardController;
use App\Http\Controllers\User\LeaderboardController;
use App\Http\Controllers\User\MonthlyReferralLeaderboardController;
use App\Http\Controllers\User\TopPerformersController;
use App\Http\Controllers\User\ReferralController;
use App\Http\Controllers\User\MyPositionController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::middleware('auth')->group(function () {

    // Role অনুযায়ী Dashboard Redirect
    Route::get('/dashboard', function () {

        $user = auth()->user();

        return match ($user->role) {
            'super_admin' => redirect()->route('super-admin.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'user' => redirect()->route('user.dashboard'),
            default => abort(403, 'Invalid user role.'),
        };

    })->name('dashboard');


    // Super Admin Routes
    Route::middleware('role:super_admin')
        ->prefix('super-admin')
        ->name('super-admin.')
        ->group(function () {

            Route::get('/dashboard', function () {
                return view('super-admin.dashboard');
            })->name('dashboard');
        Route::get('/admins', [AdminController::class, 'index'])
            ->name('admins.index');

        Route::get('/admins/create', [AdminController::class, 'create'])
            ->name('admins.create');

        Route::post('/admins', [AdminController::class, 'store'])
            ->name('admins.store');
        });


    // Admin Routes
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');

        });


    // User Routes
    Route::middleware('role:user')
        ->prefix('user')
        ->name('user.')
        ->group(function () {

            Route::get('/dashboard', function () {
                return view('user.dashboard');
            })->name('dashboard');
            Route::get('/account-summary', [AccountSummaryController::class, 'index'])
            ->name('account-summary');

            Route::get('/notifications', [NotificationController::class, 'index'])
            ->name('notifications.index');

            Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
                ->name('notifications.read');

            Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
                ->name('notifications.read-all');

            Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])
                ->name('notifications.destroy');
            Route::get('/personal-information', [PersonalInformationController::class, 'index'])
                ->name('personal-information.index');

            Route::put('/personal-information', [PersonalInformationController::class, 'update'])
                ->name('personal-information.update');

            Route::get('/country-information', [CountryInformationController::class, 'index'])
            ->name('country-information.index');

            Route::get('/payment-information', [PaymentInformationController::class, 'index'])
            ->name('payment-information.index');

            Route::get('/change-password', [ChangePasswordController::class, 'index'])
            ->name('change-password.index');

            Route::put('/change-password', [ChangePasswordController::class, 'update'])
                ->name('change-password.update');
            Route::get('/kyc-verification', [KycVerificationController::class, 'index'])
            ->name('kyc-verification.index');

            Route::get('/wallet', [WalletController::class, 'index'])
            ->name('wallet.index');

                Route::get('/wallet/bdt', [BdtWalletController::class, 'index'])
        ->name('wallet.bdt');

        Route::get('/wallet/usd', [UsdWalletController::class, 'index'])
        ->name('wallet.usd');
    Route::get('/withdraw', [WalletController::class, 'withdraw'])
        ->name('withdraw');

    Route::get('/withdrawal-methods', [WalletController::class, 'withdrawalMethods'])
    ->name('withdrawal-methods');
    Route::get('/deposit-history', [DepositHistoryController::class, 'index'])
    ->name('deposit-history.index');
    Route::get('/withdrawal-history', [WithdrawalHistoryController::class, 'index'])
    ->name('withdrawal-history.index');

    Route::get('/transaction-history', [TransactionHistoryController::class, 'index'])
    ->name('transaction-history.index');

    Route::get('/available-projects', [AvailableProjectController::class, 'index'])
    ->name('available-projects.index');

    Route::get('/project-details', [ProjectDetailsController::class, 'index'])
    ->name('project-details.index');

Route::get('/project-details/{slug}', [ProjectDetailsController::class, 'show'])
    ->name('project-details.show');

    Route::get('/my-shares', [MySharesController::class, 'index'])
    ->name('my-shares.index');
Route::get('/purchase-history', [PurchaseHistoryController::class, 'index'])
    ->name('purchase-history.index');

    Route::get('/sales-history', [SalesHistoryController::class, 'index'])
    ->name('sales-history.index');

    Route::get('/share-transfer', [ShareTransferController::class, 'index'])
    ->name('share-transfer.index');

    Route::get('/investment-summary', [InvestmentSummaryController::class, 'index'])
    ->name('investment-summary.index');
Route::get('/project-wise-records', [ProjectWiseRecordController::class, 'index'])
    ->name('project-wise-records.index');

    Route::get('/profit-loss-history', [ProfitLossHistoryController::class, 'index'])
    ->name('profit-loss-history.index');

    Route::get('/maturity-information', [MaturityInformationController::class, 'index'])
    ->name('maturity-information.index');

            Route::get('/rewards', [RewardController::class, 'index'])
    ->name('rewards.index');

    Route::get('/reward-categories', [RewardCategoryController::class, 'index'])
        ->name('reward-categories.index');
    
        Route::get('/reward-history', [RewardHistoryController::class, 'index'])
    ->name('reward-history.index');

    Route::get('/achievement-rewards', [AchievementRewardController::class, 'index'])
    ->name('achievement-rewards.index');

// Leaderboard
Route::prefix('leaderboard')->name('leaderboard.')->controller(LeaderboardController::class)->group(function () {

    Route::get('/', 'index')->name('index');

    Route::get('/monthly', 'monthly')->name('monthly');

    Route::get('/top-performers', 'topPerformers')->name('top-performers');

});

// Referral System
Route::prefix('referrals')->name('referrals.')->controller(ReferralController::class)->group(function () {

    Route::get('/link', 'link')->name('link');

    Route::get('/', 'index')->name('index');

    Route::get('/commission', 'commission')->name('commission');

    Route::get('/statistics', 'statistics')->name('statistics');

});

Route::prefix('my-position')
    ->name('my-position.')
    ->controller(MyPositionController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');

        Route::get('/history', 'history')->name('history');

        Route::get('/locked-shares', 'lockedShares')->name('locked-shares');

        Route::get('/achievement-history', 'achievements')->name('achievements');

    });
    
        });

});