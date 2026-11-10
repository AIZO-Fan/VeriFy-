<div class="container" style="max-width: 1200px; margin: 24px auto 0; padding: 0 20px;">
    @if(session('success'))
        <div style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.3); color: #16a34a; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-weight: 600;">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #dc2626; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px;">
            <div style="font-weight: 700; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Periksa kendala berikut:
            </div>
            <ul style="margin: 0; padding-left: 24px; font-size: 0.95rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Admin Top Bar --}}
    <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; padding: 16px 20px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; box-shadow: 0 4px 14px var(--theme-shadow);">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(32, 180, 203, 0.15); color: #20b4cb; display: flex; align-items: center; justify-content: center;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
            </div>
            <div>
                <div style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #20b4cb;">VeriFy Control Center</div>
                <div style="font-size: 1.15rem; font-weight: 800; color: var(--theme-heading);">Portal Administrasi</div>
            </div>
        </div>

        {{-- Nav Tabs --}}
        <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
            <a href="{{ route('admin.dashboard') }}"
               style="padding: 8px 14px; border-radius: 10px; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; {{ request()->routeIs('admin.dashboard') ? 'background: #20b4cb; color: #ffffff;' : 'background: var(--theme-card-soft); color: var(--theme-text); border: 1px solid var(--theme-border);' }}">
                Ringkasan
            </a>
            <a href="{{ route('admin.modules.index') }}"
               style="padding: 8px 14px; border-radius: 10px; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; {{ request()->routeIs('admin.modules.*') ? 'background: #20b4cb; color: #ffffff;' : 'background: var(--theme-card-soft); color: var(--theme-text); border: 1px solid var(--theme-border);' }}">
                Modul Pembelajaran
            </a>
            <a href="{{ route('admin.simulator.index') }}"
               style="padding: 8px 14px; border-radius: 10px; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; {{ request()->routeIs('admin.simulator.*') ? 'background: #20b4cb; color: #ffffff;' : 'background: var(--theme-card-soft); color: var(--theme-text); border: 1px solid var(--theme-border);' }}">
                Scam Simulator
            </a>
            <a href="{{ route('admin.users.index') }}"
               style="padding: 8px 14px; border-radius: 10px; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; {{ request()->routeIs('admin.users.*') ? 'background: #20b4cb; color: #ffffff;' : 'background: var(--theme-card-soft); color: var(--theme-text); border: 1px solid var(--theme-border);' }}">
                Pengguna
            </a>
            <a href="{{ route('admin.scans.index') }}"
               style="padding: 8px 14px; border-radius: 10px; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; {{ request()->routeIs('admin.scans.*') ? 'background: #20b4cb; color: #ffffff;' : 'background: var(--theme-card-soft); color: var(--theme-text); border: 1px solid var(--theme-border);' }}">
                Monitoring Scam
            </a>
        </div>
    </div>
</div>

