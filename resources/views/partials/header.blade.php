<header class="app-header">

    <div class="header-left">


<button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">☰</button>
        <h2>@yield('page-title', 'Dashboard')</h2>

    </div>

    <div class="header-right">

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

                <a href="{{ route('user.personal-information.index') }}" class="dropdown-item">
                    <span>👤</span>
                    My Profile
                </a>

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
</script>