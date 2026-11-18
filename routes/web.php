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
use App\Http\Controllers\User\SupportController;
use App\Http\Controllers\User\LocalAgentController;
use App\Http\Controllers\User\ResearchRequestController as UserResearchRequestController;
use App\Http\Controllers\User\WalletConvertController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\IncomeHistoryController;

use App\Http\Controllers\SuperAdmin\CompanyController;
use App\Http\Controllers\SuperAdmin\UserManagementController;
use App\Http\Controllers\SuperAdmin\PlanController;
use App\Http\Controllers\SuperAdmin\PlatformSettingController;
use App\Http\Controllers\SuperAdmin\ProjectController;
use App\Http\Controllers\SuperAdmin\ProjectCategoryController;
use App\Http\Controllers\SuperAdmin\ProjectTemplateController;
use App\Http\Controllers\SuperAdmin\ProfileReviewController;
use App\Http\Controllers\SuperAdmin\BannerController;
use App\Http\Controllers\SuperAdmin\NoticeController;
use App\Http\Controllers\SuperAdmin\AnnouncementController;
use App\Http\Controllers\SuperAdmin\FaqController;
use App\Http\Controllers\SuperAdmin\RewardCategoryController as AdminRewardCategoryController;
use App\Http\Controllers\SuperAdmin\LeaderboardSettingController;
use App\Http\Controllers\SuperAdmin\ReferralSettingController;
use App\Http\Controllers\SuperAdmin\PositionSettingController;
use App\Http\Controllers\SuperAdmin\PlatformReportController;
use App\Http\Controllers\SuperAdmin\ActivityLogController;
use App\Http\Controllers\SuperAdmin\ProductCatalogueReportController;
use App\Http\Middleware\LogUserActivity;
use App\Http\Controllers\SuperAdmin\NotificationBroadcastController;
use App\Http\Controllers\SuperAdmin\SupportTicketController;
use App\Http\Controllers\SuperAdmin\ContactMessageController;
use App\Http\Controllers\SuperAdmin\ProfileController as SuperAdminProfileController;
use App\Http\Controllers\SuperAdmin\DepositRequestController;
use App\Http\Controllers\SuperAdmin\WithdrawRequestController;
use App\Http\Controllers\SuperAdmin\InvestmentReportController;
use App\Http\Controllers\SuperAdmin\PaymentMethodController;
use App\Http\Controllers\SuperAdmin\AgentController as SuperAdminAgentController;
use App\Http\Controllers\SuperAdmin\AgentLocationController;
use App\Http\Controllers\SuperAdmin\AgentResearchController;
use App\Http\Controllers\SuperAdmin\AgentPerformanceController;
use App\Http\Controllers\SuperAdmin\CurrencyRateController;
use App\Http\Controllers\SuperAdmin\ProfitDistributionController;

use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\ProfileController as AgentProfileController;
use App\Http\Controllers\Agent\RequestController as AgentRequestController;
use App\Http\Controllers\Agent\MessageController as AgentMessageController;


Route::get('/', function () {

    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');

})->name('home');

Route::middleware('auth')->group(function () {

    // Role অনুযায়ী Dashboard Redirect
    Route::get('/dashboard', function () {

        $user = auth()->user();

        return match ($user->role) {
            'super_admin' => redirect()->route('super-admin.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'agent'       => redirect()->route('agent.dashboard'),
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

        // Company Management
        Route::get('/companies', [CompanyController::class, 'index'])
            ->name('companies.index');

        Route::get('/companies/create', [CompanyController::class, 'create'])
            ->name('companies.create');

        Route::post('/companies', [CompanyController::class, 'store'])
            ->name('companies.store');

        Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])
            ->name('companies.edit');

        Route::put('/companies/{company}', [CompanyController::class, 'update'])
            ->name('companies.update');

        Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])
            ->name('companies.destroy');

            // User Management
Route::get('/users', [UserManagementController::class, 'index'])
    ->name('users.index');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])
    ->name('users.show');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])
    ->name('users.edit');

Route::put('/users/{user}', [UserManagementController::class, 'update'])
    ->name('users.update');

    Route::patch('/users/{user}/status', [UserManagementController::class, 'updateStatus'])
    ->name('users.update-status');
// Platform Plans Management
Route::resource('plans', PlanController::class);

Route::patch('/plans/{plan}/status', [PlanController::class, 'updateStatus'])
    ->name('plans.update-status');

// Platform Settings
Route::get('/platform-settings', [PlatformSettingController::class, 'index'])
    ->name('platform-settings.index');

Route::put('/platform-settings', [PlatformSettingController::class, 'update'])
    ->name('platform-settings.update');

Route::get('/projects', [ProjectController::class, 'index'])
    ->name('projects.index');

Route::post('/projects', [ProjectController::class, 'store'])
    ->name('projects.store');

Route::put('/projects/{project}', [ProjectController::class, 'update'])
    ->name('projects.update');

Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])
    ->name('projects.destroy');


// Project Categories
Route::get('/project-categories', [ProjectCategoryController::class, 'index'])
    ->name('project-categories.index');

Route::post('/project-categories', [ProjectCategoryController::class, 'store'])
    ->name('project-categories.store');

Route::put('/project-categories/{projectCategory}', [ProjectCategoryController::class, 'update'])
    ->name('project-categories.update');

Route::delete('/project-categories/{projectCategory}', [ProjectCategoryController::class, 'destroy'])
    ->name('project-categories.destroy');

// Product Templates Management
Route::get('/project-templates', [ProjectTemplateController::class, 'index'])
    ->name('project-templates.index');

Route::post('/project-templates', [ProjectTemplateController::class, 'store'])
    ->name('project-templates.store');

Route::put('/project-templates/{projectTemplate}', [ProjectTemplateController::class, 'update'])
    ->name('project-templates.update');

Route::delete('/project-templates/{projectTemplate}', [ProjectTemplateController::class, 'destroy'])
    ->name('project-templates.destroy');

// Profile Review Management
Route::prefix('profile-reviews')
    ->name('profile-reviews.')
    ->controller(ProfileReviewController::class)
    ->group(function () {

        Route::get('/', 'index')
            ->name('index');

        Route::get('/{user}', 'show')
            ->name('show');

        Route::put('/{user}', 'update')
            ->name('update');
});


// Banner Management
Route::prefix('banners')
    ->name('banners.')
    ->controller(BannerController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{banner}', 'show')->name('show');
        Route::put('/{banner}', 'update')->name('update');
        Route::delete('/{banner}', 'destroy')->name('destroy');
    });


    // Notice Board
Route::prefix('notices')
    ->name('notices.')
    ->controller(NoticeController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');

        Route::post('/', 'store')->name('store');

        Route::get('/{notice}', 'show')->name('show');

        Route::put('/{notice}', 'update')->name('update');

        Route::delete('/{notice}', 'destroy')->name('destroy');

    });

// Announcement Management
Route::prefix('announcements')
    ->name('announcements.')
    ->controller(AnnouncementController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');

        Route::post('/', 'store')->name('store');

        Route::get('/{announcement}', 'show')->name('show');

        Route::put('/{announcement}', 'update')->name('update');

        Route::delete('/{announcement}', 'destroy')->name('destroy');

    });

/*
|--------------------------------------------------------------------------
| FAQ Management
|--------------------------------------------------------------------------
*/

Route::prefix('faqs')
    ->name('faqs.')
    ->controller(FaqController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');

        Route::post('/', 'store')->name('store');

        Route::get('/{faq}', 'show')->name('show');

        Route::put('/{faq}', 'update')->name('update');

        Route::delete('/{faq}', 'destroy')->name('destroy');

    });

// Reward Categories
Route::resource('reward-categories', AdminRewardCategoryController::class)
    ->only(['index', 'store', 'show', 'update', 'destroy']);

// Leaderboard Settings
Route::get('/leaderboard-settings', [LeaderboardSettingController::class, 'index'])->name('leaderboard-settings.index');
Route::put('/leaderboard-settings', [LeaderboardSettingController::class, 'update'])->name('leaderboard-settings.update');

// Referral Settings
Route::get('/referral-settings', [ReferralSettingController::class, 'index'])->name('referral-settings.index');
Route::put('/referral-settings', [ReferralSettingController::class, 'update'])->name('referral-settings.update');

// Position / Rank Settings
Route::resource('position-settings', PositionSettingController::class)
    ->parameters(['position-settings' => 'positionRank'])
    ->only(['index', 'store', 'show', 'update', 'destroy']);
Route::post('/position-settings/recalculate', [PositionSettingController::class, 'recalculate'])
    ->name('position-settings.recalculate');

    Route::get('/reports/platform', [PlatformReportController::class, 'index'])->name('reports.platform');
Route::get('/reports/activity-logs', [ActivityLogController::class, 'index'])->name('reports.activity-logs');
Route::get('/reports/product-catalogue', [ProductCatalogueReportController::class, 'index'])->name('reports.product-catalogue');

Route::resource('notifications', NotificationBroadcastController::class)
    ->parameters(['notifications' => 'broadcast'])
    ->only(['index', 'store', 'destroy']);

Route::resource('support-tickets', SupportTicketController::class)
    ->parameters(['support-tickets' => 'ticket'])
    ->only(['index', 'show', 'update', 'destroy']);
Route::post('/support-tickets/{ticket}/reply', [SupportTicketController::class, 'reply'])
    ->name('support-tickets.reply');

Route::resource('contact-messages', ContactMessageController::class)
    ->parameters(['contact-messages' => 'contactMessage'])
    ->only(['index', 'show', 'update', 'destroy']);
Route::post('/contact-messages/{contactMessage}/reply', [ContactMessageController::class, 'reply'])
    ->name('contact-messages.reply');

// My Profile
Route::get('/profile', [SuperAdminProfileController::class, 'index'])
    ->name('profile.index');

Route::put('/profile', [SuperAdminProfileController::class, 'update'])
    ->name('profile.update');

    // Deposit Requests
Route::get('/deposit-requests', [DepositRequestController::class, 'index'])
    ->name('deposit-requests.index');
Route::patch('/deposit-requests/{depositRequest}/approve', [DepositRequestController::class, 'approve'])
    ->name('deposit-requests.approve');
Route::patch('/deposit-requests/{depositRequest}/reject', [DepositRequestController::class, 'reject'])
    ->name('deposit-requests.reject');
Route::get('/deposit-requests/{depositRequest}/screenshot', [DepositRequestController::class, 'screenshot'])
    ->name('deposit-requests.screenshot');

// Withdraw Requests
Route::get('/withdraw-requests', [WithdrawRequestController::class, 'index'])
    ->name('withdraw-requests.index');
Route::patch('/withdraw-requests/{withdrawRequest}/approve', [WithdrawRequestController::class, 'approve'])
    ->name('withdraw-requests.approve');
Route::patch('/withdraw-requests/{withdrawRequest}/reject', [WithdrawRequestController::class, 'reject'])
    ->name('withdraw-requests.reject');

// Investment Reports & Monitoring
Route::prefix('investments')
    ->name('investments.')
    ->controller(InvestmentReportController::class)
    ->group(function () {
        Route::get('/summary', 'summary')->name('summary');
        Route::get('/project-wise', 'projectWise')->name('project-wise');
        Route::get('/profit-loss', 'profitLoss')->name('profit-loss');
        Route::get('/maturity', 'maturity')->name('maturity');
    });

// Payment Methods
Route::prefix('payment-methods')
    ->name('payment-methods.')
    ->controller(PaymentMethodController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{paymentMethod}', 'update')->name('update');
        Route::patch('/{paymentMethod}/toggle', 'toggle')->name('toggle');
        Route::delete('/{paymentMethod}', 'destroy')->name('destroy');
    });

// ===== Agent Management =====
Route::prefix('agents')->name('agents.')->controller(SuperAdminAgentController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{agent}', 'show')->name('show');
    Route::get('/{agent}/edit', 'edit')->name('edit');
    Route::put('/{agent}', 'update')->name('update');
    Route::patch('/{agent}/verify', 'toggleVerify')->name('verify');
    Route::patch('/{agent}/approval', 'approval')->name('approval');
    Route::patch('/{agent}/status', 'toggleStatus')->name('status');
});

Route::prefix('agent-locations')->name('agent-locations.')->controller(AgentLocationController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/countries', 'storeCountry')->name('countries.store');
    Route::put('/countries/{country}', 'updateCountry')->name('countries.update');
    Route::delete('/countries/{country}', 'destroyCountry')->name('countries.destroy');
    Route::post('/districts', 'storeDistrict')->name('districts.store');
    Route::put('/districts/{district}', 'updateDistrict')->name('districts.update');
    Route::delete('/districts/{district}', 'destroyDistrict')->name('districts.destroy');
});

Route::controller(AgentResearchController::class)->group(function () {
    Route::get('/research-requests', 'index')->name('research-requests.index');
    Route::get('/research-requests/{researchRequest}', 'show')->name('research-requests.show');
    Route::get('/research-requests/{researchRequest}/report', 'download')->name('research-requests.download');
    Route::get('/research-reports', 'reports')->name('research-reports.index');
});

Route::get('/agent-performance', [AgentPerformanceController::class, 'index'])->name('agent-performance.index');

Route::get('/currency-rates', [CurrencyRateController::class, 'index'])->name('currency-rates.index');
Route::put('/currency-rates', [CurrencyRateController::class, 'update'])->name('currency-rates.update');
Route::get('/profit-distribution', [ProfitDistributionController::class, 'index'])->name('profit-distribution.index');
Route::post('/profit-distribution', [ProfitDistributionController::class, 'run'])->name('profit-distribution.run');

        });

// Agent Routes
Route::middleware('role:agent')
    ->prefix('agent')
    ->name('agent.')
    ->group(function () {

        Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [AgentProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [AgentProfileController::class, 'update'])->name('profile.update');
        Route::patch('/availability', [AgentProfileController::class, 'toggleAvailability'])->name('availability');

        Route::post('/payment-methods', [AgentProfileController::class, 'storePaymentMethod'])
            ->middleware('throttle:10,1')->name('payment-methods.store');
        Route::patch('/payment-methods/{paymentMethod}/toggle', [AgentProfileController::class, 'togglePaymentMethod'])
            ->name('payment-methods.toggle');
        Route::delete('/payment-methods/{paymentMethod}', [AgentProfileController::class, 'destroyPaymentMethod'])
            ->name('payment-methods.destroy');

        Route::prefix('requests')->name('requests.')->controller(AgentRequestController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{researchRequest}', 'show')->name('show');
            Route::post('/{researchRequest}/quote', 'quote')->name('quote');
            Route::patch('/{researchRequest}/reject', 'reject')->name('reject');
            Route::patch('/{researchRequest}/confirm-payment', 'confirmPayment')->name('confirm-payment');
            Route::post('/{researchRequest}/report', 'submitReport')->name('report');
            Route::get('/{researchRequest}/report', 'download')->name('download');
        });

        Route::prefix('messages')->name('messages.')->controller(AgentMessageController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{user}', 'show')->name('show');
            Route::post('/{user}', 'store')->middleware('throttle:30,1')->name('store');
        });
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

            Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
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

            Route::get('/announcements', [NotificationController::class, 'announcements'])
    ->name('announcements');

    Route::get('/important-notices', [NotificationController::class, 'importantNotices'])
    ->name('important-notices');

            Route::get('/personal-information', [PersonalInformationController::class, 'index'])
                ->name('personal-information.index');

            Route::put('/personal-information', [PersonalInformationController::class, 'update'])
                ->name('personal-information.update');

            Route::get('/country-information', [CountryInformationController::class, 'index'])
            ->name('country-information.index');

            Route::get('/payment-information', [PaymentInformationController::class, 'index'])
            ->name('payment-information.index');
            Route::post('/payment-information', [PaymentInformationController::class, 'store'])
                ->middleware('throttle:10,1')
                ->name('payment-information.store');

            Route::delete('/payment-information/{paymentAccount}', [PaymentInformationController::class, 'destroy'])
                ->name('payment-information.destroy');
            Route::get('/change-password', [ChangePasswordController::class, 'index'])
            ->name('change-password.index');

            Route::put('/change-password', [ChangePasswordController::class, 'update'])
                ->name('change-password.update');
            Route::get('/kyc-verification', [KycVerificationController::class, 'index'])
            ->name('kyc-verification.index');

            Route::get('/wallet', [WalletController::class, 'index'])
            ->name('wallet.index');

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
Route::post('/project-details/{slug}/buy', [ProjectDetailsController::class, 'buy'])
    ->middleware('throttle:20,1')
    ->name('project-details.buy');

Route::post('/my-shares/{project}/sell', [MySharesController::class, 'sell'])
    ->middleware('throttle:20,1')
    ->name('my-shares.sell');

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

        Route::get('/income-history', [IncomeHistoryController::class, 'index'])->name('income-history.index');

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

Route::prefix('my-position')->name('my-position.')->controller(MyPositionController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/history', 'history')->name('history');
    Route::get('/locked-shares', 'lockedShares')->name('locked-shares');
    Route::get('/achievement-history', 'achievements')->name('achievements');
    Route::post('/lock', 'lock')->middleware('throttle:20,1')->name('lock');
    Route::post('/unlock', 'unlock')->middleware('throttle:20,1')->name('unlock');
});
    
        Route::prefix('support')->name('support.')->controller(SupportController::class)->group(function () {

            Route::get('/submit-ticket', 'submitTicket')->name('submit-ticket');

            Route::get('/my-tickets', 'myTickets')->name('my-tickets');

            Route::get('/contact', 'contact')->name('contact');

        });

        Route::get('/deposit', [WalletController::class, 'deposit'])->name('deposit');
Route::post('/deposit', [WalletController::class, 'storeDeposit'])
    ->middleware('throttle:10,1')
    ->name('deposit.store');

Route::post('/withdraw', [WalletController::class, 'storeWithdraw'])
    ->middleware('throttle:10,1')
    ->name('withdraw.store');

// Local Agents
Route::prefix('local-agents')->name('agents.')->controller(LocalAgentController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{agent}/messages', 'messages')->name('messages');
    Route::post('/{agent}/messages', 'sendMessage')->middleware('throttle:30,1')->name('messages.send');
});

Route::prefix('research-requests')->name('research.')->controller(UserResearchRequestController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create/{agent}', 'create')->name('create');
    Route::post('/{agent}', 'store')->middleware('throttle:10,1')->name('store');
    Route::get('/{researchRequest}', 'show')->name('show');
    Route::patch('/{researchRequest}/accept', 'accept')->name('accept');
    Route::patch('/{researchRequest}/cancel', 'cancel')->name('cancel');
    Route::patch('/{researchRequest}/payment', 'submitPayment')->name('payment');
    Route::get('/{researchRequest}/report', 'downloadReport')->name('download');
});

Route::get('/wallet/convert', [WalletConvertController::class, 'create'])->name('wallet.convert');
Route::post('/wallet/convert', [WalletConvertController::class, 'store'])
    ->middleware('throttle:10,1')->name('wallet.convert.store');

        });

});