<div class="va-shell va-nav-shell">

    @if(session('success'))
        <div
            class="va-alert va-alert--success"
            role="status"
        >
            {{ session('success') }}
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div
            class="va-alert va-alert--danger"
            role="alert"
        >
            <strong>Periksa kendala berikut:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="va-topbar">

        <div class="va-identity">
            <span class="va-icon" aria-hidden="true">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.7"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <rect x="3" y="3" width="7" height="7" rx="1" />
                    <rect x="14" y="3" width="7" height="7" rx="1" />
                    <rect x="3" y="14" width="7" height="7" rx="1" />
                    <rect x="14" y="14" width="7" height="7" rx="1" />
                </svg>
            </span>

            <div>
                <span class="va-eyebrow">
                    VERIFY CONTROL CENTER
                </span>

                <p class="va-brand-title">
                    Portal Administrasi
                </p>
            </div>
        </div>

        <nav
            class="va-tabs"
            aria-label="Navigasi administrasi"
        >
            <a
                href="{{ route('admin.dashboard') }}"
                class="va-tab {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}"
                @if(request()->routeIs('admin.dashboard'))
                    aria-current="page"
                @endif
            >
                Ringkasan
            </a>

            <a
                href="{{ route('admin.modules.index') }}"
                class="va-tab {{ request()->routeIs('admin.modules.*') ? 'is-active' : '' }}"
                @if(request()->routeIs('admin.modules.*'))
                    aria-current="page"
                @endif
            >
                Modul Pembelajaran
            </a>

            <a
                href="{{ route('admin.simulator.index') }}"
                class="va-tab {{ request()->routeIs('admin.simulator.*') ? 'is-active' : '' }}"
                @if(request()->routeIs('admin.simulator.*'))
                    aria-current="page"
                @endif
            >
                Scam Simulator
            </a>

            <a
                href="{{ route('admin.users.index') }}"
                class="va-tab {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}"
                @if(request()->routeIs('admin.users.*'))
                    aria-current="page"
                @endif
            >
                Pengguna
            </a>

            <a
                href="{{ route('admin.scans.index') }}"
                class="va-tab {{ request()->routeIs('admin.scans.*') ? 'is-active' : '' }}"
                @if(request()->routeIs('admin.scans.*'))
                    aria-current="page"
                @endif
            >
                Monitoring Scam
            </a>
        </nav>

    </div>
</div>