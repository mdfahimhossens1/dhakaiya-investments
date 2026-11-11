<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="main-sidebar" id="mainSidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="brand-logo">
        <a href="{{ route('user.dashboard') }}" class="sidebar-logo">
    <img src="{{ asset('images/dhakaiya-logo.png') }}" alt="Dhakaiya Investment Logo">
    </a>
        </div>
    </div>

    {{-- User Profile --}}
    <div class="sidebar-user">
        <div class="sidebar-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="sidebar-user-info">
            <h4>{{ auth()->user()->name }}</h4>
            <span>{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</span>
        </div>
        <span class="online-dot"></span>
    </div>

    {{-- USER SIDEBAR --}}
    @if(auth()->user()->role === 'user')

    @php
        // Kon group er moddhe kon route active ta ekhane define kora ache
        $profileActive = request()->routeIs(
            'user.personal-information.*', 'user.country-information.*',
            'user.payment-information.*', 'user.change-password.*', 'user.kyc-verification.*'
        );
        $walletActive = request()->routeIs(
            'user.wallet.*', 'user.deposit-history.*',
            'user.withdrawal-history.*', 'user.transaction-history.*'
        );
        $sharesActive = request()->routeIs(
            'user.available-projects.*', 'user.project-details.*', 'user.my-shares.*',
            'user.purchase-history.*', 'user.sales-history.*', 'user.share-transfer.*'
        );
        $recordsActive = request()->routeIs(
            'user.investment-summary.*', 'user.project-wise-records.*',
            'user.profit-loss-history.*', 'user.maturity-information.*'
        );
        $rewardsActive = request()->routeIs(
            'user.rewards.*', 'user.reward-categories.*',
            'user.reward-history.*', 'user.achievement-rewards.*'
        );
        $leaderboardActive = request()->routeIs('user.leaderboard.*');
        $referralActive = request()->routeIs('user.referrals.*');
        $positionActive = request()->routeIs('user.my-position.*');
        $withdrawActive = request()->routeIs('user.withdraw', 'user.withdrawal-methods');
    @endphp

    <nav class="sidebar-navigation">

        <div class="menu-section">
            <div class="menu-section-title">MAIN MENU</div>

            <a href="{{ route('user.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <span class="menu-icon">⌂</span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('user.account-summary') }}"
                class="sidebar-link {{ request()->routeIs('user.account-summary') ? 'active' : '' }}">
                <span class="menu-icon">◉</span>
                <span>Account Summary</span>
            </a>

        </div>

        <div class="menu-section">
            <div class="menu-section-title">MY ACCOUNT</div>

            <details class="menu-group {{ $profileActive ? 'active' : '' }}" {{ $profileActive ? 'open' : '' }}>
                <summary>
                    <span class="menu-icon">♙</span>
                    <span>My Profile</span>
                    <span class="menu-arrow">›</span>
                </summary>
                <div class="submenu">
                    <a href="{{ route('user.personal-information.index') }}" class="{{ request()->routeIs('user.personal-information.*') ? 'active' : '' }}">Personal Information</a>
                    <a href="{{ route('user.country-information.index') }}" class="{{ request()->routeIs('user.country-information.*') ? 'active' : '' }}">Country Information</a>
                    <a href="{{ route('user.payment-information.index') }}" class="{{ request()->routeIs('user.payment-information.*') ? 'active' : '' }}">Payment Information</a>
                    <a href="{{ route('user.change-password.index') }}" class="{{ request()->routeIs('user.change-password.*') ? 'active' : '' }}">Change Password</a>
                    <a href="{{ route('user.kyc-verification.index') }}" class="{{ request()->routeIs('user.kyc-verification.*') ? 'active' : '' }}">KYC Verification</a>
                </div>
            </details>

            <details class="menu-group {{ $walletActive ? 'active' : '' }}" {{ $walletActive ? 'open' : '' }}>
                <summary>
                    <span class="menu-icon">▣</span>
                    <span>My Wallet</span>
                    <span class="menu-arrow">›</span>
                </summary>
                <div class="submenu">
                    <a href="{{ route('user.wallet.index') }}" class="{{ request()->routeIs('user.wallet.index') ? 'active' : '' }}">Wallet Overview</a>
                    <a href="{{ route('user.deposit-history.index') }}" class="{{ request()->routeIs('user.deposit-history.*') ? 'active' : '' }}">Deposit History</a>
                    <a href="{{ route('user.withdrawal-history.index') }}" class="{{ request()->routeIs('user.withdrawal-history.*') ? 'active' : '' }}">Withdrawal History</a>
                    <a href="{{ route('user.transaction-history.index') }}" class="{{ request()->routeIs('user.transaction-history.*') ? 'active' : '' }}">Transaction History</a>
                </div>
            </details>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">INVESTMENT</div>

            <details class="menu-group {{ $sharesActive ? 'active' : '' }}" {{ $sharesActive ? 'open' : '' }}>
                <summary>
                    <span class="menu-icon">◈</span>
                    <span>Shares</span>
                    <span class="menu-arrow">›</span>
                </summary>
                <div class="submenu">
                    <a href="{{ route('user.available-projects.index') }}" class="{{ request()->routeIs('user.available-projects.*') ? 'active' : '' }}">Available Projects</a>
                    <a href="{{ route('user.project-details.index') }}" class="{{ request()->routeIs('user.project-details.*') ? 'active' : '' }}">Project Details</a>
                    <a href="{{ route('user.my-shares.index') }}" class="{{ request()->routeIs('user.my-shares.*') ? 'active' : '' }}">My Shares</a>
                    <a href="{{ route('user.purchase-history.index') }}" class="{{ request()->routeIs('user.purchase-history.*') ? 'active' : '' }}">Purchase History</a>
                    <a href="{{ route('user.sales-history.index') }}" class="{{ request()->routeIs('user.sales-history.*') ? 'active' : '' }}">Sales History</a>
                    <a href="{{ route('user.share-transfer.index') }}" class="{{ request()->routeIs('user.share-transfer.*') ? 'active' : '' }}">Share Transfer / Resale</a>
                </div>
            </details>

            <details class="menu-group {{ $recordsActive ? 'active' : '' }}" {{ $recordsActive ? 'open' : '' }}>
                <summary>
                    <span class="menu-icon">▤</span>
                    <span>Investment Records</span>
                    <span class="menu-arrow">›</span>
                </summary>
                <div class="submenu">
                    <a href="{{ route('user.investment-summary.index') }}" class="{{ request()->routeIs('user.investment-summary.*') ? 'active' : '' }}">Investment Summary</a>
                    <a href="{{ route('user.project-wise-records.index') }}" class="{{ request()->routeIs('user.project-wise-records.*') ? 'active' : '' }}">Project-wise Records</a>
                    <a href="{{ route('user.profit-loss-history.index') }}" class="{{ request()->routeIs('user.profit-loss-history.*') ? 'active' : '' }}">Profit & Loss History</a>
                    <a href="{{ route('user.maturity-information.index') }}" class="{{ request()->routeIs('user.maturity-information.*') ? 'active' : '' }}">Maturity Information</a>
                </div>
            </details>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">REWARDS & RANKING</div>

            <details class="menu-group {{ $rewardsActive ? 'active' : '' }}" {{ $rewardsActive ? 'open' : '' }}>
                <summary>
                    <span class="menu-icon">🎁</span>
                    <span>Rewards</span>
                    <span class="menu-arrow">›</span>
                </summary>
                <div class="submenu">
                    <a href="{{ route('user.rewards.index') }}" class="{{ request()->routeIs('user.rewards.*') ? 'active' : '' }}">My Rewards</a>
                    <a href="{{ route('user.reward-categories.index') }}" class="{{ request()->routeIs('user.reward-categories.*') ? 'active' : '' }}">Reward Categories</a>
                    <a href="{{ route('user.reward-history.index') }}" class="{{ request()->routeIs('user.reward-history.*') ? 'active' : '' }}">Reward History</a>
                    <a href="{{ route('user.achievement-rewards.index') }}" class="{{ request()->routeIs('user.achievement-rewards.*') ? 'active' : '' }}">Achievement Rewards</a>
                </div>
            </details>

            <details class="menu-group {{ $leaderboardActive ? 'active' : '' }}" {{ $leaderboardActive ? 'open' : '' }}>
                <summary>
                    <span class="menu-icon">♛</span>
                    <span>Leaderboard</span>
                    <span class="menu-arrow">›</span>
                </summary>
                <div class="submenu">
                    <a href="{{ route('user.leaderboard.index') }}" class="{{ request()->routeIs('user.leaderboard.index') ? 'active' : '' }}">Resell Leaderboard</a>
                    <a href="{{ route('user.leaderboard.monthly') }}" class="{{ request()->routeIs('user.leaderboard.monthly') ? 'active' : '' }}">Monthly Leaderboard</a>
                    <a href="{{ route('user.leaderboard.top-performers') }}" class="{{ request()->routeIs('user.leaderboard.top-performers') ? 'active' : '' }}">Top Performers</a>
                </div>
            </details>

            <details class="menu-group {{ $referralActive ? 'active' : '' }}" {{ $referralActive ? 'open' : '' }}>
                <summary>
                    <span class="menu-icon">♧</span>
                    <span>Referral System</span>
                    <span class="menu-arrow">›</span>
                </summary>
                <div class="submenu">
                    <a href="{{ route('user.referrals.index') }}" class="{{ request()->routeIs('user.referrals.index') ? 'active' : '' }}">My Referral</a>
                    <a href="{{ route('user.referrals.link') }}" class="{{ request()->routeIs('user.referrals.link') ? 'active' : '' }}">Referral Link</a>
                    <a href="{{ route('user.referrals.commission') }}" class="{{ request()->routeIs('user.referrals.commission') ? 'active' : '' }}">Referral Commission</a>
                    <a href="{{ route('user.referrals.statistics') }}" class="{{ request()->routeIs('user.referrals.statistics') ? 'active' : '' }}">Referral Statistics</a>
                </div>
            </details>

            <details class="menu-group {{ $positionActive ? 'active' : '' }}" {{ $positionActive ? 'open' : '' }}>
                <summary>
                    <span class="menu-icon">♔</span>
                    <span>My Position</span>
                    <span class="menu-arrow">›</span>
                </summary>
                <div class="submenu">
                    <a href="{{ route('user.my-position.index') }}" class="{{ request()->routeIs('user.my-position.index') ? 'active' : '' }}">Current Position</a>
                    <a href="{{ route('user.my-position.history') }}" class="{{ request()->routeIs('user.my-position.history') ? 'active' : '' }}">Position History</a>
                    <a href="{{ route('user.my-position.locked-shares') }}" class="{{ request()->routeIs('user.my-position.locked-shares') ? 'active' : '' }}">Locked Share Records</a>
                    <a href="{{ route('user.my-position.achievements') }}" class="{{ request()->routeIs('user.my-position.achievements') ? 'active' : '' }}">Achievement History</a>
                </div>
            </details>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">PAYMENT</div>

            <details class="menu-group {{ $withdrawActive ? 'active' : '' }}" {{ $withdrawActive ? 'open' : '' }}>
                <summary>
                    <span class="menu-icon">⇧</span>
                    <span>Withdraw</span>
                    <span class="menu-arrow">›</span>
                </summary>
                <div class="submenu">
                    <a href="{{ route('user.withdraw') }}" class="{{ request()->routeIs('user.withdraw') ? 'active' : '' }}">Request Withdrawal</a>
                    <a href="{{ route('user.withdrawal-methods') }}" class="{{ request()->routeIs('user.withdrawal-methods') ? 'active' : '' }}">Withdrawal Methods</a>
                </div>
            </details>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">HELP & SUPPORT</div>

            <details class="menu-group">
                <summary>
                    <span class="menu-icon">♧</span>
                    <span>Notifications</span>
                    <span class="menu-arrow">›</span>
                </summary>
                <div class="submenu">
                    <a href="{{ route('user.notifications.index') }}" class="sidebar-link {{ request()->routeIs('user.notifications.*') ? 'active' : '' }}">System Notifications</a>
                    <a href="{{ route('user.important-notices') }}" class="{{ request()->routeIs('user.important-notices') ? 'active' : '' }}">Important Notices</a>
                    <a href="{{ route('user.announcements') }}" class="{{ request()->routeIs('user.announcements') ? 'active' : '' }}">Announcements</a>
                </div>
            </details>

            <details class="menu-group">
                <summary>
                    <span class="menu-icon">☏</span>
                    <span>Support</span>
                    <span class="menu-arrow">›</span>
                </summary>
                <div class="submenu">
                    <a href="{{ route('user.support.submit-ticket') }}" class="{{ request()->routeIs('user.support.submit-ticket') ? 'active' : '' }}">Submit Ticket</a>
                    <a href="{{ route('user.support.my-tickets') }}" class="{{ request()->routeIs('user.support.my-tickets') ? 'active' : '' }}">My Tickets</a>
                    <a href="{{ route('user.support.contact') }}" class="{{ request()->routeIs('user.support.contact') ? 'active' : '' }}">Contact Support</a>
                </div>
            </details>
        </div>

        {{-- Sidebar Bottom --}}
        <div class="sidebar-bottom">
            <div class="sidebar-support">
                <div class="support-icon">?</div>
                <div>
                    <h4>Need Help?</h4>
                    <p>Contact our support team.</p>
                </div>
            </div>
        </div>

    </nav>

    {{-- ADMIN SIDEBAR --}}
    @else

    <nav class="sidebar-navigation">

        <div class="menu-section">
            <div class="menu-section-title">MAIN MENU</div>

            <a href="{{ route('dashboard') }}"
                class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="menu-icon">⌂</span>
                <span>Dashboard</span>
            </a>
        </div>

        @if(auth()->user()->role === 'super_admin')

        <div class="menu-section">
            <div class="menu-section-title">PLATFORM MANAGEMENT</div>

            <a href="{{ route('super-admin.admins.index') }}"
                class="sidebar-link {{ request()->routeIs('super-admin.admins.*') ? 'active' : '' }}">
                <span class="menu-icon">♙</span>
                <span>Admin Management</span>
            </a>

            <a href="#" class="sidebar-link">
                <span class="menu-icon">♧</span>
                <span>User Management</span>
            </a>

            <a href="#" class="sidebar-link">
                <span class="menu-icon">▣</span>
                <span>Subscription Plans</span>
            </a>

            <a href="#" class="sidebar-link">
                <span class="menu-icon">⚙</span>
                <span>Platform Settings</span>
            </a>
        </div>

        @else

        <div class="menu-section">
            <div class="menu-section-title">MANAGEMENT</div>

            <a href="#" class="sidebar-link">
                <span class="menu-icon">♙</span>
                <span>Users</span>
            </a>

            <a href="#" class="sidebar-link">
                <span class="menu-icon">▤</span>
                <span>Reports</span>
            </a>

            <a href="#" class="sidebar-link">
                <span class="menu-icon">⚙</span>
                <span>Settings</span>
            </a>
        </div>

        @endif

        <div class="sidebar-bottom">
            <div class="sidebar-support">
                <div class="support-icon">?</div>
                <div>
                    <h4>Need Help?</h4>
                    <p>Contact our support team.</p>
                </div>
            </div>
        </div>

    </nav>

    @endif

</aside>


<style>
    :root {
        --sidebar-w: 280px;
    }

    html, body {
        overflow-x: hidden;
    }

    /* ===== Sidebar base ===== */
    .main-sidebar {
        position: relative;
        flex: 0 0 var(--sidebar-w);
        width: var(--sidebar-w);
        min-height: 100vh;
        height: auto;
        overflow: visible;
        background: #ffffff;
        border-right: 1px solid #e9eef0;
        box-shadow: 3px 0 15px rgba(15, 23, 42, 0.03);
        z-index: 1040;
        transition: margin-left 0.3s ease, transform 0.3s ease;
    }

    .sidebar-navigation {
        padding: 0 14px 20px;
    }
.brand-logo a img {
    width: 55px;
    height: 55px;
    margin: auto;
}
    /* ===== Toggle button ===== */
    .sidebar-toggle {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        border: 1px solid #e5ebe8;
        background: #ffffff;
        color: #087f5b;
        border-radius: 10px;
        font-size: 19px;
        line-height: 1;
        cursor: pointer;
        display: inline-grid;
        place-items: center;
        transition: all 0.2s;
    }

    .sidebar-toggle:hover {
        background: #087f5b;
        color: #ffffff;
    }

    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        z-index: 1030;
        display: none;
    }

    .dashboard-table,
    .table-responsive table {
        min-width: 550px;
    }

    /* ===== Desktop collapse ===== */
    @media (min-width: 992px) {
        body.sidebar-collapsed .main-sidebar {
            margin-left: calc(-1 * var(--sidebar-w));
            box-shadow: none;
        }
    }

    /* ===== Mobile / Tablet drawer ===== */
    @media (max-width: 991px) {
        .main-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            height: 100vh;
            height: 100dvh;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            margin-left: 0;
            transform: translateX(-100%);
        }

        body.sidebar-open .main-sidebar {
            transform: translateX(0);
            box-shadow: 8px 0 30px rgba(15, 23, 42, 0.15);
        }

        body.sidebar-open .sidebar-overlay {
            display: block;
        }
    }

    /* ===== Sidebar UI ===== */
    .sidebar-brand {
        height: 84px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 22px;
        border-bottom: 1px solid #f0f2f4;
    }

    .brand-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #087f5b;
        display: grid;
        place-items: center;
        color: #ffffff;
        font-size: 24px;
        font-weight: 800;
    }

    .brand-info h3 {
        color: #172b25;
        font-size: 19px;
        font-weight: 800;
    }

    .brand-info span {
        font-size: 10px;
        color: #087f5b;
        font-weight: 700;
        letter-spacing: 2px;
    }

    .sidebar-user {
        display: flex;
        align-items: center;
        gap: 11px;
        margin: 18px 15px;
        padding: 14px;
        background: #f7faf9;
        border: 1px solid #edf1ef;
        border-radius: 11px;
    }

    .sidebar-avatar {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        background: #dcfce7;
        color: #087f5b;
        display: grid;
        place-items: center;
        border-radius: 50%;
        font-size: 16px;
        font-weight: 800;
    }

    .sidebar-user-info {
        flex: 1;
        min-width: 0;
    }

    .sidebar-user-info h4 {
        color: #172b25;
        font-size: 14px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .sidebar-user-info span {
        display: block;
        color: #87948e;
        font-size: 12px;
        margin-top: 4px;
    }

    .online-dot {
        width: 9px;
        height: 9px;
        background: #22c55e;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .menu-section {
        margin-bottom: 22px;
    }

    .menu-section-title {
        color: #9aa5a0;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.2px;
        padding: 0 10px;
        margin: 20px 0 9px;
    }

    /* ===== Main links & group headers ===== */
    .sidebar-link,
    .menu-group summary {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 46px;
        padding: 10px 12px;
        margin-bottom: 4px;
        border-radius: 8px;
        color: #475569;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        list-style: none;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .sidebar-link:hover,
    .menu-group summary:hover {
        background: #f0fdf4;
        color: #087f5b;
    }

    /* Active: single link */
    .sidebar-link.active {
        background: #e8f7ef;
        color: #087f5b;
        font-weight: 700;
    }

    /* Active: menu group (summary highlight) */
    .menu-group.active > summary {
        background: #e8f7ef;
        color: #087f5b;
        font-weight: 700;
    }

    .menu-icon {
        width: 22px;
        flex-shrink: 0;
        font-size: 18px;
        text-align: center;
        color: #7c8b84;
    }

    .sidebar-link.active .menu-icon,
    .menu-group.active > summary .menu-icon,
    .sidebar-link:hover .menu-icon,
    .menu-group summary:hover .menu-icon {
        color: #087f5b;
    }

    .menu-arrow {
        margin-left: auto;
        font-size: 20px;
        color: #9ca3af;
        transition: transform 0.2s;
    }

    .menu-group.active > summary .menu-arrow {
        color: #087f5b;
    }

    .menu-group[open] .menu-arrow {
        transform: rotate(90deg);
    }

    .menu-group summary::-webkit-details-marker {
        display: none;
    }

    /* ===== Submenu ===== */
    .submenu {
        padding: 2px 0 6px 42px;
    }

    .submenu a {
        display: block;
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
        padding: 10px 12px;
        margin-bottom: 2px;
        border-left: 2px solid #e8eeeb;
        text-decoration: none;
        transition: all 0.2s;
    }

    .submenu a:hover {
        color: #087f5b;
        border-left-color: #087f5b;
        background: #f7fcf8;
    }

    .submenu a.active {
        color: #087f5b;
        font-weight: 700;
        border-left-color: #087f5b;
        background: #e8f7ef;
    }

    .sidebar-bottom {
        padding: 15px 0 0;
        margin-top: 10px;
    }

    .sidebar-support {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 14px;
        background: #f5f9f7;
        border: 1px solid #e9f0ec;
        border-radius: 10px;
    }

    .support-icon {
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        background: #087f5b;
        color: white;
        border-radius: 50%;
        font-weight: 700;
    }

    .sidebar-support h4 {
        font-size: 13px;
        color: #24352e;
    }

    .sidebar-support p {
        font-size: 11px;
        color: #8a9690;
        margin-top: 4px;
    }
</style>

<script>
    (function () {
        if (window.__dhakaiyaSidebarInit) return;
        window.__dhakaiyaSidebarInit = true;

        const body = document.body;
        const KEY = 'dhakaiya_sidebar_collapsed';
        const isMobile = () => window.innerWidth <= 991;

        // Desktop e last state mone rakhbe
        try {
            if (!isMobile() && localStorage.getItem(KEY) === '1') {
                body.classList.add('sidebar-collapsed');
            }
        } catch (e) {}

        function toggleSidebar() {
            if (isMobile()) {
                body.classList.toggle('sidebar-open');
            } else {
                const collapsed = body.classList.toggle('sidebar-collapsed');
                try { localStorage.setItem(KEY, collapsed ? '1' : '0'); } catch (e) {}
            }

            setTimeout(function () {
                window.dispatchEvent(new Event('resize'));
            }, 350);
        }

        document.addEventListener('click', function (e) {
            if (e.target.closest('#sidebarToggle')) {
                e.preventDefault();
                e.stopImmediatePropagation();
                toggleSidebar();
            }
        }, true);

        document.getElementById('sidebarOverlay').addEventListener('click', function () {
            body.classList.remove('sidebar-open');
        });

        window.addEventListener('resize', function () {
            if (!isMobile()) body.classList.remove('sidebar-open');
        });

        /* ===== Active state handling ===== */
        const sidebar = document.getElementById('mainSidebar');
        const groups = sidebar.querySelectorAll('.menu-group');
        const links = sidebar.querySelectorAll('.sidebar-link');
        const subLinks = sidebar.querySelectorAll('.submenu a');

        function clearAllActive() {
            links.forEach(l => l.classList.remove('active'));
            subLinks.forEach(l => l.classList.remove('active'));
            groups.forEach(g => g.classList.remove('active'));
        }

        // Group summary te click: oita active hobe, onno group gulo close + inactive
        groups.forEach(function (group) {
            const summary = group.querySelector('summary');

            summary.addEventListener('click', function () {
                // details er default toggle click er por hoy, tai ekhane current state ulta
                const willOpen = !group.hasAttribute('open');

                if (willOpen) {
                    groups.forEach(function (other) {
                        if (other !== group) {
                            other.removeAttribute('open');
                            // Jar bhitore active sublink nai, tar active class tule dao
                            if (!other.querySelector('.submenu a.active')) {
                                other.classList.remove('active');
                            }
                        }
                    });
                    // Standalone link gulo inactive (kintu server-side active page thakle ota ei page er jonno ঠিক)
                    links.forEach(l => l.classList.remove('active'));
                    group.classList.add('active');
                } else if (!group.querySelector('.submenu a.active')) {
                    group.classList.remove('active');
                }
            });
        });

        // Standalone link click: ekta active, baki shob inactive
        links.forEach(function (link) {
            link.addEventListener('click', function () {
                clearAllActive();
                link.classList.add('active');
            });
        });

        // Submenu link click: oi link + parent group active, baki inactive
        subLinks.forEach(function (sub) {
            sub.addEventListener('click', function () {
                clearAllActive();
                sub.classList.add('active');
                const parent = sub.closest('.menu-group');
                if (parent) parent.classList.add('active');
            });
        });
    })();
</script>