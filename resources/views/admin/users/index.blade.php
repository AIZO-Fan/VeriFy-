@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    @include('admin.partials.nav')

    <section
        class="va-shell va-users-page"
        aria-labelledby="va-users-title"
    >
        {{-- HEADER --}}
        <header class="va-management-heading">
            <div class="va-management-heading-copy">
                <span class="va-eyebrow">
                    KELOLA AKUN
                </span>

                <h1 id="va-users-title">
                    Manajemen Pengguna
                </h1>

                <p>
                    Pantau aktivitas akun dan kelola hak akses pengguna.
                </p>
            </div>
        </header>

        <div class="va-data-card">

            {{-- SEARCH DAN FILTER --}}
            <div class="va-users-toolbar">
                <div class="va-users-toolbar-heading">
                    <h2 id="va-users-list-title">
                        Daftar pengguna
                    </h2>

                    <p>
                        Cari akun berdasarkan nama, email, atau role.
                    </p>
                </div>

                <form
                    class="va-users-filter"
                    action="{{ route('admin.users.index') }}"
                    method="GET"
                    role="search"
                    aria-label="Cari dan filter pengguna"
                >
                    <div class="va-users-search">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            aria-hidden="true"
                        >
                            <circle cx="10.5" cy="10.5" r="6.5" />
                            <path d="M16 16L21 21" />
                        </svg>

                        <input
                            class="va-control va-users-search-input"
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama atau email..."
                            aria-label="Cari nama atau email"
                        >
                    </div>

                    <select
                        class="va-control va-users-role-filter"
                        name="role"
                        aria-label="Filter role pengguna"
                    >
                        <option value="">Semua role</option>

                        <option
                            value="admin"
                            {{ request('role') == 'admin' ? 'selected' : '' }}
                        >
                            Admin
                        </option>

                        <option
                            value="user"
                            {{ request('role') == 'user' ? 'selected' : '' }}
                        >
                            User
                        </option>
                    </select>

                    <button
                        class="va-button va-button--primary"
                        type="submit"
                    >
                        Cari
                    </button>

                    @if(request()->filled('search') || request()->filled('role'))
                        <a
                            class="va-users-reset"
                            href="{{ route('admin.users.index') }}"
                        >
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- TABEL --}}
            <div
                class="va-table-scroll"
                role="region"
                aria-labelledby="va-users-list-title"
                tabindex="0"
            >
                <table class="va-table va-users-table">
                    <thead>
                        <tr>
                            <th scope="col">Pengguna</th>
                            <th scope="col">Email</th>
                            <th scope="col">Aktivitas</th>
                            <th scope="col">Terdaftar</th>
                            <th scope="col">Role</th>

                            <th
                                scope="col"
                                class="va-cell-actions"
                            >
                                Ubah role
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="va-user-identity">
                                        <span
                                            class="va-user-avatar"
                                            aria-hidden="true"
                                        >
                                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                        </span>

                                        <div class="va-user-name-wrap">
                                            <span class="va-user-name">
                                                {{ $user->name }}
                                            </span>

                                            @if($user->id === auth()->id())
                                                <span class="va-user-self">
                                                    Anda
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="va-user-email">
                                        {{ $user->email }}
                                    </span>
                                </td>

                                <td>
                                    <div class="va-user-activity">
                                        <span>
                                            <strong>
                                                {{ $user->scan_histories_count }}
                                            </strong>
                                            pemeriksaan
                                        </span>

                                        <span>
                                            <strong>
                                                {{ $user->simulation_attempts_count }}
                                            </strong>
                                            simulasi
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <span class="va-user-date">
                                        {{ $user->created_at
                                            ? $user->created_at->format('d M Y')
                                            : '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span
                                        class="va-badge {{ $user->role === 'admin' ? 'va-badge--admin' : '' }}"
                                    >
                                        {{ $user->role === 'admin' ? 'Admin' : 'User' }}
                                    </span>
                                </td>

                                <td class="va-cell-actions">
                                    <form
                                        class="va-user-role-form"
                                        action="{{ route('admin.users.role', $user) }}"
                                        method="POST"
                                    >
                                        @csrf

                                        <select
                                            class="va-control va-user-role-select"
                                            name="role"
                                            aria-label="Role untuk {{ $user->name }}"
                                        >
                                            <option
                                                value="user"
                                                {{ $user->role === 'user' ? 'selected' : '' }}
                                            >
                                                User
                                            </option>

                                            <option
                                                value="admin"
                                                {{ $user->role === 'admin' ? 'selected' : '' }}
                                            >
                                                Admin
                                            </option>
                                        </select>

                                        <button
                                            class="va-action-button va-user-save"
                                            type="submit"
                                            aria-label="Simpan role untuk {{ $user->name }}"
                                        >
                                            Simpan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="6"
                                    class="va-table-empty-cell"
                                >
                                    <div class="va-table-empty">
                                        <span
                                            class="va-icon"
                                            aria-hidden="true"
                                        >
                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <circle cx="9" cy="7" r="4" />

                                                <path d="M2 21V19A4 4 0 0 1 6 15H12A4 4 0 0 1 16 19V21" />

                                                <path d="M17 4A4 4 0 0 1 17 11M22 21V19A4 4 0 0 0 19 15" />
                                            </svg>
                                        </span>

                                        <h3>Tidak ada pengguna ditemukan</h3>

                                        <p>
                                            Coba kata kunci lain atau ubah
                                            filter role yang digunakan.
                                        </p>

                                        @if(request()->filled('search') || request()->filled('role'))
                                            <a
                                                class="va-button"
                                                href="{{ route('admin.users.index') }}"
                                            >
                                                Reset pencarian
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($users->hasPages())
                <div class="va-users-pagination">
                    {{ $users->appends(request()->only(['search', 'role']))->links() }}
                </div>
            @endif

        </div>
    </section>
@endsection