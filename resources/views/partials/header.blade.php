<header class="app-header">

    <div class="header-left">

        <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">☰</button>
        <h2>@yield('page-title', 'Dashboard')</h2>

    </div>

    <div class="header-right">

        {{-- ==== NEW: APP DOWNLOAD ICON ==== --}}
        <button type="button"
                class="app-dl-btn"
                data-tooltip="Download App"
                aria-label="Download our mobile app"
                onclick="openAppModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
        </button>

        {{-- Notification --}}
        <button class="notification-btn" type="button" aria-label="Notifications">
            ♧
            <span></span>
        </button>

        {{-- Profile Dropdown --}}
        <div class="profile-dropdown">

            <button class="profile-dropdown-toggle" type="button"
                    onclick="toggleProfileDropdown()">

                <div class="header-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="profile-info">
                    <h4>{{ auth()->user()->name }}</h4>
                    <span>
                        {{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}
                    </span>
                </div>

                <span class="dropdown-arrow">⌄</span>

            </button>

            <div class="profile-dropdown-menu" id="profileDropdown">

                <div class="dropdown-user-info">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>{{ auth()->user()->phone }}</span>
                </div>

                <div class="dropdown-divider"></div>

                @php
                    $profileRoute = match (auth()->user()->role) {
                        'super_admin' => 'super-admin.profile.index',
                        'admin' => 'admin.profile.index',
                        'user' => 'user.personal-information.index',
                        default => null,
                    };
                @endphp

                @if($profileRoute && Route::has($profileRoute))
                    <a href="{{ route($profileRoute) }}" class="dropdown-item">
                        <span>👤</span>
                        My Profile
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="dropdown-item logout-item">
                        <span>↪</span>
                        Logout
                    </button>
                </form>

            </div>

        </div>

    </div>

</header>


{{-- ==== NEW: APP DOWNLOAD MODAL ==== --}}
<div class="app-modal-overlay" id="appModalOverlay" onclick="closeAppModalOnOverlay(event)">
    <div class="app-modal" role="dialog" aria-modal="true" aria-labelledby="appModalTitle">

        <button type="button" class="app-modal-close" onclick="closeAppModal()" aria-label="Close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>

        <div class="app-modal-hero">
            <div class="app-modal-phone">
                <div class="app-modal-phone-screen">
                    <div class="app-modal-phone-logo">D</div>
                </div>
            </div>

            <div class="app-modal-badge">
                <span class="app-modal-badge-dot"></span>
                Coming Soon
            </div>
        </div>

        <div class="app-modal-body">
            <h3 id="appModalTitle">Dhakaiya Investments App</h3>
            <p>
                Take your investments with you. Get real-time updates,
                manage your portfolio, and trade shares anytime — right from
                your phone.
            </p>

            <div class="app-modal-features">
                <div class="app-modal-feature">
                    <span>📊</span> Live portfolio
                </div>
                <div class="app-modal-feature">
                    <span>🔔</span> Instant alerts
                </div>
                <div class="app-modal-feature">
                    <span>🔐</span> Secure login
                </div>
            </div>

            <div class="app-modal-stores">
                {{-- Play Store --}}
                <a href="#" class="store-btn" onclick="event.preventDefault(); storeComingSoon('Google Play Store');">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3.609 1.814L13.792 12 3.61 22.186a.996.996 0 0 1-.61-.92V2.734a1 1 0 0 1 .609-.92zm10.89 10.893l2.302 2.302-10.937 6.333 8.635-8.635zm3.199-3.198l2.807 1.626a1 1 0 0 1 0 1.73l-2.808 1.626L15.199 12l2.499-2.491zM5.864 2.658L16.802 8.99l-2.303 2.303-8.635-8.635z"/>
                    </svg>
                    <div>
                        <small>GET IT ON</small>
                        <strong>Google Play</strong>
                    </div>
                </a>

                {{-- App Store --}}
                <a href="#" class="store-btn" onclick="event.preventDefault(); storeComingSoon('Apple App Store');">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
                    </svg>
                    <div>
                        <small>Download on the</small>
                        <strong>App Store</strong>
                    </div>
                </a>
            </div>

            <p class="app-modal-note">
                <span>✦</span>
                We're putting the finishing touches on our mobile apps.
                They'll be available very soon!
            </p>
        </div>

    </div>
</div>


<style>
    .app-header {
        height: var(--header-height);
        background: #fff;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 28px;
        position: relative;
        z-index: 100;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header-left h2 {
        font-size: 19px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .menu-toggle {
        display: none;
        border: 0;
        background: #edf7f3;
        color: var(--primary);
        padding: 9px 12px;
        border-radius: 8px;
        font-size: 20px;
        cursor: pointer;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    /* ==== NEW: APP DOWNLOAD ICON ==== */
    .app-dl-btn {
        position: relative;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #edf7f3;
        border: 1px solid #d3ebe1;
        color: #087f5b;
        cursor: pointer;
        transition: transform .25s, background .25s, border-color .25s, color .25s, box-shadow .25s;
        padding: 0;
    }

    .app-dl-btn:hover {
        background: linear-gradient(135deg, #10b981, #087f5b);
        color: #fff;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(8, 127, 91, .3);
    }

    .app-dl-btn svg {
        width: 18px;
        height: 18px;
        display: block;
    }

    .app-dl-btn::after {
        content: attr(data-tooltip);
        position: absolute;
        top: calc(100% + 10px);
        left: 50%;
        transform: translateX(-50%) translateY(-4px);
        background: #0b1428;
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 6px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity .2s, transform .2s;
        z-index: 1000;
    }

    .app-dl-btn::before {
        content: "";
        position: absolute;
        top: calc(100% + 4px);
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-bottom-color: #0b1428;
        opacity: 0;
        transition: opacity .2s;
        z-index: 1000;
    }

    .app-dl-btn:hover::after {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    .app-dl-btn:hover::before {
        opacity: 1;
    }

    /* ==== NEW: APP DOWNLOAD MODAL ==== */
    .app-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(11, 20, 40, 0);
        backdrop-filter: blur(0px);
        opacity: 0;
        visibility: hidden;
        transition: opacity .3s, visibility .3s, background .3s, backdrop-filter .3s;
    }

    .app-modal-overlay.show {
        opacity: 1;
        visibility: visible;
        background: rgba(11, 20, 40, .65);
        backdrop-filter: blur(6px);
    }

    .app-modal {
        position: relative;
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 30px 80px rgba(0, 0, 0, .35), 0 0 0 1px rgba(255, 255, 255, .05) inset;
        transform: translateY(30px) scale(.94);
        opacity: 0;
        transition: transform .4s cubic-bezier(.2, .9, .3, 1.2), opacity .3s;
    }

    .app-modal-overlay.show .app-modal {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .app-modal-close {
        position: absolute;
        top: 14px;
        right: 14px;
        z-index: 3;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 0;
        background: rgba(255, 255, 255, .2);
        backdrop-filter: blur(6px);
        color: #fff;
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: background .2s, transform .2s;
    }

    .app-modal-close:hover {
        background: rgba(255, 255, 255, .35);
        transform: rotate(90deg);
    }

    .app-modal-close svg {
        width: 16px;
        height: 16px;
    }

    /* Hero area */
    .app-modal-hero {
        position: relative;
        height: 180px;
        background:
            radial-gradient(400px 200px at 20% 20%, rgba(16, 185, 129, .35), transparent 60%),
            radial-gradient(400px 200px at 80% 80%, rgba(8, 127, 91, .4), transparent 60%),
            linear-gradient(140deg, #087f5b, #065f46);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .app-modal-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255, 255, 255, .06) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, .06) 1px, transparent 1px);
        background-size: 22px 22px;
        -webkit-mask-image: radial-gradient(ellipse at center, #000 20%, transparent 75%);
        mask-image: radial-gradient(ellipse at center, #000 20%, transparent 75%);
    }

    .app-modal-phone {
        position: relative;
        z-index: 2;
        width: 74px;
        height: 124px;
        border-radius: 14px;
        background: linear-gradient(145deg, #0b1428, #172844);
        padding: 6px;
        box-shadow: 0 14px 30px rgba(0, 0, 0, .3), 0 0 0 1px rgba(255, 255, 255, .1) inset;
        transform: rotate(-6deg);
        animation: phoneFloat 4s ease-in-out infinite;
    }

    @keyframes phoneFloat {
        50% { transform: rotate(-6deg) translateY(-6px); }
    }

    .app-modal-phone::before {
        content: "";
        position: absolute;
        top: 6px;
        left: 50%;
        transform: translateX(-50%);
        width: 24px;
        height: 4px;
        background: #0b1428;
        border-radius: 0 0 4px 4px;
        z-index: 2;
    }

    .app-modal-phone-screen {
        width: 100%;
        height: 100%;
        border-radius: 9px;
        background: linear-gradient(160deg, #10b981, #087f5b);
        display: grid;
        place-items: center;
    }

    .app-modal-phone-logo {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: rgba(255, 255, 255, .95);
        color: #087f5b;
        font-weight: 800;
        font-size: 18px;
        display: grid;
        place-items: center;
        font-family: 'Manrope', sans-serif;
    }

    .app-modal-badge {
        position: absolute;
        z-index: 3;
        top: 16px;
        left: 16px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 12px;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .25);
        border-radius: 30px;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .3px;
        backdrop-filter: blur(8px);
    }

    .app-modal-badge-dot {
        width: 6px;
        height: 6px;
        background: #fbbf24;
        border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(251, 191, 36, .6);
        animation: badgePulse 2s infinite;
    }

    @keyframes badgePulse {
        70% { box-shadow: 0 0 0 8px rgba(251, 191, 36, 0); }
        100% { box-shadow: 0 0 0 0 rgba(251, 191, 36, 0); }
    }

    /* Body */
    .app-modal-body {
        padding: 24px 24px 22px;
        text-align: center;
    }

    .app-modal-body h3 {
        font-size: 20px;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 10px;
        letter-spacing: -.3px;
    }

    .app-modal-body > p {
        font-size: 13px;
        color: #64748b;
        line-height: 1.65;
        margin: 0 0 18px;
    }

    .app-modal-features {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .app-modal-feature {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        background: #f8fafc;
        border: 1px solid #e8edf0;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        color: #475569;
    }

    .app-modal-feature span {
        font-size: 13px;
    }

    .app-modal-stores {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 18px;
    }

    .store-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        background: #0b1428;
        border-radius: 10px;
        color: #fff;
        text-decoration: none;
        transition: transform .2s, background .2s, box-shadow .2s;
        text-align: left;
    }

    .store-btn:hover {
        background: #172844;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(11, 20, 40, .3);
    }

    .store-btn svg {
        width: 22px;
        height: 22px;
        flex-shrink: 0;
        color: #fff;
    }

    .store-btn div {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
        min-width: 0;
    }

    .store-btn small {
        font-size: 8px;
        font-weight: 500;
        letter-spacing: .5px;
        text-transform: uppercase;
        color: #b7c2d4;
    }

    .store-btn strong {
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .app-modal-note {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        padding: 12px;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 10px;
        font-size: 11px;
        color: #92400e;
        line-height: 1.55;
        text-align: left;
        margin: 0;
    }

    .app-modal-note span {
        flex-shrink: 0;
        font-size: 13px;
        margin-top: 1px;
    }

    /* Notification */

    .notification-btn {
        position: relative;
        border: 0;
        background: #f1f5f4;
        color: #475569;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 19px;
    }

    .notification-btn span {
        position: absolute;
        top: 7px;
        right: 8px;
        width: 7px;
        height: 7px;
        background: #ef4444;
        border-radius: 50%;
    }

    /* Profile */

    .profile-dropdown {
        position: relative;
    }

    .profile-dropdown-toggle {
        display: flex;
        align-items: center;
        gap: 10px;
        border: 0;
        background: transparent;
        cursor: pointer;
        padding: 5px;
        border-radius: 8px;
    }

    .profile-dropdown-toggle:hover {
        background: #f8fafc;
    }

    .header-avatar {
        width: 40px;
        height: 40px;
        display: grid;
        place-items: center;
        background: #dcfce7;
        color: #087f5b;
        border-radius: 50%;
        font-weight: 700;
        font-size: 15px;
    }

    .profile-info {
        text-align: left;
    }

    .profile-info h4 {
        font-size: 12px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 3px;
    }

    .profile-info span {
        font-size: 10px;
        color: var(--muted);
    }

    .dropdown-arrow {
        font-size: 18px;
        color: #64748b;
        margin-left: 3px;
    }

    /* Dropdown Menu */

    .profile-dropdown-menu {
        display: none;
        position: absolute;
        top: calc(100% + 12px);
        right: 0;
        width: 220px;
        background: #fff;
        border: 1px solid #e8edf0;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.10);
        padding: 8px;
        z-index: 999;
    }

    .profile-dropdown-menu.show {
        display: block;
    }

    .dropdown-user-info {
        display: flex;
        flex-direction: column;
        padding: 12px;
        gap: 5px;
    }

    .dropdown-user-info strong {
        font-size: 13px;
        color: #1e293b;
    }

    .dropdown-user-info span {
        font-size: 11px;
        color: #84919f;
    }

    .dropdown-divider {
        height: 1px;
        background: #edf0f2;
        margin: 5px 0;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 11px 12px;
        border: 0;
        background: transparent;
        text-decoration: none;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        border-radius: 7px;
        cursor: pointer;
        text-align: left;
    }

    .dropdown-item:hover {
        background: #f1f5f4;
        color: var(--primary);
    }

    .logout-item {
        color: #e11d48;
    }

    .logout-item:hover {
        background: #fff1f2;
        color: #e11d48;
    }

    @media(max-width: 992px) {
        .menu-toggle {
            display: block;
        }
    }

    @media(max-width: 576px) {
        .app-header {
            padding: 0 12px;
        }

        .header-right {
            gap: 10px;
        }

        .app-dl-btn::after,
        .app-dl-btn::before {
            display: none;
        }

        .profile-info {
            display: none;
        }

        .header-left h2 {
            font-size: 16px;
        }

        .dropdown-arrow {
            display: none;
        }

        .profile-dropdown-menu {
            width: 200px;
        }

        .app-modal {
            max-width: 100%;
            border-radius: 16px;
        }

        .app-modal-hero {
            height: 150px;
        }

        .app-modal-body {
            padding: 20px 18px 18px;
        }

        .app-modal-body h3 {
            font-size: 18px;
        }

        .app-modal-stores {
            grid-template-columns: 1fr;
        }
    }
</style>


<script>
    function toggleProfileDropdown() {
        document.getElementById('profileDropdown').classList.toggle('show');
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.profile-dropdown');

        if (dropdown && !dropdown.contains(event.target)) {
            document.getElementById('profileDropdown').classList.remove('show');
        }
    });

    /* ==== NEW: APP DOWNLOAD MODAL ==== */
    function openAppModal() {
        document.getElementById('appModalOverlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeAppModal() {
        document.getElementById('appModalOverlay').classList.remove('show');
        document.body.style.overflow = '';
    }

    function closeAppModalOnOverlay(event) {
        if (event.target.id === 'appModalOverlay') {
            closeAppModal();
        }
    }

    function storeComingSoon(storeName) {
        // modal-এ একটা temporary toast message দেখাবে
        const modal = document.querySelector('.app-modal');
        if (!modal) return;

        // পুরনো toast থাকলে সরাও
        const oldToast = modal.querySelector('.app-modal-toast');
        if (oldToast) oldToast.remove();

        const toast = document.createElement('div');
        toast.className = 'app-modal-toast';
        toast.textContent = storeName + ' — coming very soon!';
        toast.style.cssText = `
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            background: #0b1428;
            color: #fff;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(0,0,0,.4);
            opacity: 0;
            transition: opacity .25s, transform .25s;
            z-index: 10;
            white-space: nowrap;
        `;
        modal.appendChild(toast);

        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translate(-50%, -50%) scale(1)';
        });

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translate(-50%, -50%) scale(0.9)';
            setTimeout(() => toast.remove(), 250);
        }, 2000);
    }

    // ESC দিয়ে modal বন্ধ
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const overlay = document.getElementById('appModalOverlay');
            if (overlay && overlay.classList.contains('show')) {
                closeAppModal();
            }
        }
    });
</script>