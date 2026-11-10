@extends('layouts.app')

@section('title', 'Manajemen Scam Simulator')

@section('content')
    @include('admin.partials.nav')

    <section
        class="va-shell va-simulator-page"
        aria-labelledby="va-simulator-title"
    >
        {{-- HEADER HALAMAN --}}
        <header class="va-management-heading">
            <div class="va-management-heading-copy">
                <span class="va-eyebrow">
                    KELOLA KONTEN
                </span>

                <h1 id="va-simulator-title">
                    Scam Simulator
                </h1>

                <p>
                    Kelola skenario percakapan interaktif
                    untuk latihan mengenali penipuan digital.
                </p>
            </div>

            <a
                class="va-button va-button--primary"
                href="{{ route('admin.simulator.create') }}"
            >
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    aria-hidden="true"
                >
                    <path d="M12 5V19M5 12H19" />
                </svg>

                Buat skenario baru
            </a>
        </header>

        {{-- DAFTAR SKENARIO --}}
        <div class="va-data-card">
            <div class="va-data-card-heading">
                <div>
                    <h2 id="va-simulator-list-title">
                        Daftar skenario
                    </h2>

                    <p>
                        Atur materi, tingkat kesulitan,
                        dan status publikasi skenario.
                    </p>
                </div>

                <span class="va-data-caption">
                    Klik status untuk mengubah Aktif atau Draft.
                </span>
            </div>

            <div
                class="va-table-scroll"
                role="region"
                aria-labelledby="va-simulator-list-title"
                tabindex="0"
            >
                <table class="va-table va-scenario-table">
                    <thead>
                        <tr>
                            <th scope="col">Topik</th>
                            <th scope="col">Skenario</th>
                            <th scope="col">Kesulitan</th>
                            <th scope="col">Dimainkan</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="va-cell-actions">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($scenarios as $scen)
                            @php
                                $difficultyClasses = [
                                    'beginner' => 'va-badge--success',
                                    'intermediate' => 'va-badge--warning',
                                    'advanced' => 'va-badge--danger',
                                ];

                                $difficultyClass =
                                    $difficultyClasses[$scen->difficulty] ?? '';
                            @endphp

                            <tr>
                                <td>
                                    <span class="va-badge va-badge--topic">
                                        Topik {{ $scen->topic_id }}
                                    </span>
                                </td>

                                <td class="va-scenario-name-cell">
                                    <div class="va-table-title">
                                        {{ $scen->title }}
                                    </div>

                                    <div class="va-table-meta">
                                        <span class="va-scenario-slug">
                                            /{{ $scen->slug }}
                                        </span>

                                        <span aria-hidden="true">&bull;</span>

                                        <span class="va-version">
                                            v{{ $scen->version }}
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <span
                                        class="va-badge {{ $difficultyClass }} va-difficulty-badge"
                                    >
                                        {{ ucfirst($scen->difficulty) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="va-play-count">
                                        {{ $scen->attempts_count }}
                                    </span>

                                    <span class="va-table-unit">kali</span>
                                </td>

                                <td>
                                    <form
                                        class="va-inline-form"
                                        action="{{ route('admin.simulator.toggle', $scen) }}"
                                        method="POST"
                                    >
                                        @csrf

                                        <button
                                            class="va-badge va-status-toggle {{ $scen->is_active ? 'va-badge--success' : '' }}"
                                            type="submit"
                                            aria-label="{{ $scen->is_active
                                                ? 'Ubah skenario ' . $scen->title . ' menjadi Draft'
                                                : 'Aktifkan skenario ' . $scen->title }}"
                                            title="{{ $scen->is_active
                                                ? 'Klik untuk mengubah menjadi Draft'
                                                : 'Klik untuk mengaktifkan' }}"
                                        >
                                            <span
                                                class="va-status-dot"
                                                aria-hidden="true"
                                            ></span>

                                            {{ $scen->is_active ? 'Aktif' : 'Draft' }}
                                        </button>
                                    </form>
                                </td>

                                <td class="va-cell-actions">
                                    <div class="va-table-actions">
                                        <a
                                            class="va-action-button va-action-button--edit"
                                            href="{{ route('admin.simulator.edit', $scen) }}"
                                            aria-label="Edit skenario {{ $scen->title }}"
                                        >
                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                aria-hidden="true"
                                            >
                                                <path d="M12 20H21" />
                                                <path d="M16.5 3.5A2.12 2.12 0 0 1 19.5 6.5L9 17L5 18L6 14Z" />
                                            </svg>

                                            Edit
                                        </a>

                                        <form
                                            class="va-inline-form"
                                            action="{{ route('admin.simulator.destroy', $scen) }}"
                                            method="POST"
                                            data-scenario-title="{{ $scen->title }}"
                                            onsubmit="return confirm('Hapus skenario ' + this.dataset.scenarioTitle + '?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                class="va-action-button va-action-button--delete"
                                                type="submit"
                                                aria-label="Hapus skenario {{ $scen->title }}"
                                            >
                                                <svg
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    aria-hidden="true"
                                                >
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
                                                <rect
                                                    x="3"
                                                    y="4"
                                                    width="18"
                                                    height="13"
                                                    rx="2"
                                                />

                                                <path d="M8 21H16M12 17V21" />
                                            </svg>
                                        </span>

                                        <h3>Belum ada skenario simulator</h3>

                                        <p>
                                            Tambahkan skenario pertama
                                            untuk memulai latihan interaktif.
                                        </p>

                                        <a
                                            class="va-button va-button--primary"
                                            href="{{ route('admin.simulator.create') }}"
                                        >
                                            Buat skenario baru
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