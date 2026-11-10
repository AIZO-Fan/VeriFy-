@extends('layouts.app')

@section('title', 'Manajemen Modul Pembelajaran')

@section('content')
    @include('admin.partials.nav')

    <section class="va-shell va-modules-page" aria-labelledby="va-modules-title">
        {{-- HEADER HALAMAN --}}
        <header class="va-management-heading">
            <div class="va-management-heading-copy">
                <span class="va-eyebrow">
                    KELOLA KONTEN
                </span>

                <h1 id="va-modules-title">
                    Modul Pembelajaran
                </h1>

                <p>
                    Kelola materi edukasi, urutan pembelajaran,
                    dan status modul yang ditampilkan kepada pengguna.
                </p>
            </div>

            <a class="va-button va-button--primary" href="{{ route('admin.modules.create') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    aria-hidden="true">
                    <path d="M12 5V19M5 12H19" />
                </svg>

                Tambah modul baru
            </a>
        </header>

        {{-- DAFTAR MODUL --}}
        <div class="va-data-card">
            <div class="va-data-card-heading">
                <div>
                    <h2 id="va-modules-list-title">
                        Daftar modul
                    </h2>

                    <p>
                        Atur konten dan materi pembelajaran dalam satu tempat.
                    </p>
                </div>

                <span class="va-data-caption">
                    Klik status untuk mengubah status modul.
                </span>
            </div>

            <div class="va-table-scroll" role="region" aria-labelledby="va-modules-list-title" tabindex="0">
                <table class="va-table va-modules-table">
                    <thead>
                        <tr>
                            <th scope="col">Urutan</th>
                            <th scope="col">Modul</th>
                            <th scope="col">Warna &amp; ikon</th>
                            <th scope="col">Materi</th>
                            <th scope="col">Status</th>

                            <th scope="col" class="va-cell-actions">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($modules as $mod)
                            @php
                                // Mempertahankan logika status dari kode sebelumnya.
                                $isActive = $mod->status === 'published' || $mod->is_active;

                                $moduleColor = $mod->color ?? 'blue';
                                $moduleIcon = $mod->icon ?? 'shield';

                                $sectionCount = $mod->sections_count ?? $mod->sections->count();
                            @endphp

                            <tr>
                                <td>
                                    <span class="va-module-order">
                                        #{{ $mod->sort_order ?? $mod->id }}
                                    </span>
                                </td>

                                <td class="va-module-name-cell">
                                    <div class="va-table-title">
                                        {{ $mod->title }}
                                    </div>

                                    <p class="va-module-description">
                                        {{ $mod->description ?: 'Belum ada deskripsi modul.' }}
                                    </p>
                                </td>

                                <td>
                                    <div class="va-module-appearance">
                                        <span class="va-badge va-module-color">
                                            {{ ucfirst($moduleColor) }}
                                        </span>

                                        <span class="va-module-icon-name">
                                            {{ $moduleIcon }}
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <span class="va-module-material-count">
                                        {{ $sectionCount }}
                                    </span>

                                    <span class="va-module-material-label">
                                        bagian
                                    </span>
                                </td>

                                <td>
                                    <form class="va-inline-form" action="{{ route('admin.modules.toggle', $mod) }}"
                                        method="POST">
                                        @csrf

                                        <button class="va-badge va-status-toggle {{ $isActive ? 'va-badge--success' : '' }}"
                                            type="submit" aria-label="Ubah status modul {{ $mod->title }}"
                                            title="Klik untuk mengubah status modul">
                                            <span class="va-status-dot" aria-hidden="true"></span>

                                            {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>

                                <td class="va-cell-actions">
                                    <div class="va-table-actions">

                                        <a class="va-action-button va-action-button--view"
                                            href="{{ route('lesson', $mod->id) }}" target="_blank"
                                            rel="noopener noreferrer"
                                            aria-label="Lihat modul {{ $mod->title }} di tab baru">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M14 3H21V10" />
                                                <path d="M10 14L21 3" />
                                                <path d="M21 14V19A2 2 0 0 1 19 21H5A2 2 0 0 1 3 19V5A2 2 0 0 1 5 3H10" />
                                            </svg>

                                            Lihat
                                        </a>

                                        <a class="va-action-button va-action-button--edit"
                                            href="{{ route('admin.modules.edit', $mod) }}"
                                            aria-label="Edit modul {{ $mod->title }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M12 20H21" />
                                                <path d="M16.5 3.5A2.12 2.12 0 0 1 19.5 6.5L9 17L5 18L6 14Z" />
                                            </svg>

                                            Edit
                                        </a>

                                        <form class="va-inline-form" action="{{ route('admin.modules.destroy', $mod) }}"
                                            method="POST" data-module-title="{{ $mod->title }}"
                                            onsubmit="return confirm('Yakin ingin menghapus modul ' + this.dataset.moduleTitle + '?');">
                                            @csrf
                                            @method('DELETE')

                                            <button class="va-action-button va-action-button--delete" type="submit"
                                                aria-label="Hapus modul {{ $mod->title }}">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"
                                                    aria-hidden="true">
                                                    <path d="M3 6H21M9 6V4H15V6" />
                                                    <path d="M5 6L6 20H18L19 6" />
                                                    <path d="M10 10V16M14 10V16" />
                                                </svg>

                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="va-table-empty-cell">
                                    <div class="va-table-empty">
                                        <span class="va-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 7V21" />
                                                <path
                                                    d="M3 4H8C10 4 12 5 12 7C12 5 14 4 16 4H21V18H16C14 18 12 19 12 21C12 19 10 18 8 18H3Z" />
                                            </svg>
                                        </span>

                                        <h3>Belum ada modul pembelajaran</h3>

                                        <p>
                                            Tambahkan modul pertama
                                            untuk mulai menyusun materi edukasi.
                                        </p>

                                        <a class="va-button va-button--primary" href="{{ route('admin.modules.create') }}">
                                            Tambah modul baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
