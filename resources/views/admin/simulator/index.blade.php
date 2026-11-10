@extends('layouts.app')

@section('title', 'Manajemen Scam Simulator')

@section('content')
    @include('admin.partials.nav')

    <section class="va-shell va-simulator-management" aria-labelledby="va-simulator-title">
        {{-- HEADER --}}
        <header class="va-management-heading">
            <div class="va-management-heading-copy">
                <span class="va-eyebrow">
                    SCAM SIMULATOR
                </span>

                <h1 id="va-simulator-title">
                    Manajemen simulator
                </h1>

                <p>
                    Kelola skenario percakapan interaktif untuk setiap
                    modul pembelajaran.
                </p>
            </div>

            <a class="va-button va-button--primary" href="{{ route('admin.simulator.create') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" />
                </svg>

                Buat skenario
            </a>
        </header>

        {{-- TABEL SKENARIO --}}
        <section class="va-editor-card va-simulator-table-card" aria-labelledby="va-simulator-table-title">
            <header class="va-editor-card-heading">
                <div>
                    <h2 id="va-simulator-table-title">
                        Daftar skenario
                    </h2>

                    <p>
                        Setiap modul dapat memiliki satu skenario aktif
                        atau draft.
                    </p>
                </div>
            </header>

            <div class="va-table-wrap">
                <table class="va-table">
                    <thead>
                        <tr>
                            <th scope="col">
                                Topik
                            </th>

                            <th scope="col">
                                Skenario
                            </th>

                            <th scope="col">
                                Kesulitan
                            </th>

                            <th scope="col">
                                Dimainkan
                            </th>

                            <th scope="col">
                                Status
                            </th>

                            <th scope="col" class="va-table-actions">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($simulatorRows as $row)
                            @php
                                $scenario = $row['scenario'];
                            @endphp

                            <tr>
                                <td data-label="Topik">
                                    <span class="va-topic-chip">
                                        Topik
                                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                    </span>

                                    <span class="va-table-subtext">
                                        {{ $row['module_title'] }}
                                    </span>
                                </td>

                                <td data-label="Skenario">
                                    @if ($scenario)
                                        <span class="va-table-title">
                                            {{ $scenario->title }}
                                        </span>

                                        <span class="va-table-subtext va-table-mono">
                                            /{{ $scenario->slug }}
                                            &bull;
                                            v{{ $scenario->version }}
                                        </span>
                                    @else
                                        <span class="va-table-empty">
                                            Belum ada skenario
                                        </span>
                                    @endif
                                </td>

                                <td data-label="Kesulitan">
                                    @if ($scenario)
                                        <span class="va-difficulty-badge va-difficulty--{{ $scenario->difficulty }}">
                                            {{ $scenario->difficulty }}
                                        </span>
                                    @else
                                        <span class="va-table-empty">
                                            -
                                        </span>
                                    @endif
                                </td>

                                <td data-label="Dimainkan">
                                    @if ($scenario)
                                        <span class="va-table-strong">
                                            {{ $scenario->attempts_count }} kali
                                        </span>
                                    @else
                                        <span class="va-table-empty">
                                            -
                                        </span>
                                    @endif
                                </td>

                                <td data-label="Status">
                                    @if ($scenario)
                                        <form action="{{ route('admin.simulator.toggle', $scenario) }}" method="POST">
                                            @csrf

                                            <button
                                                class="va-status-toggle {{ $scenario->is_active ? 'is-active' : 'is-draft' }}"
                                                type="submit">
                                                {{ $scenario->is_active ? 'Aktif' : 'Draft' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="va-empty-chip">
                                            Kosong
                                        </span>
                                    @endif
                                </td>

                                <td class="va-table-actions" data-label="Aksi">
                                    <div class="va-row-actions">
                                        @if ($scenario)
                                            <a class="va-row-button va-row-button--edit"
                                                href="{{ route('admin.simulator.edit', $scenario) }}">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.simulator.destroy', $scenario) }}" method="POST"
                                                data-confirm="Hapus skenario {{ $scenario->title }}?">
                                                @csrf
                                                @method('DELETE')

                                                <button class="va-row-button va-row-button--danger" type="submit">
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <a class="va-row-button va-row-button--create"
                                                href="{{ route('admin.simulator.create', ['topic_id' => $row['topic_id']]) }}">
                                                Buat skenario
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="va-table-empty-state" colspan="6">
                                    Belum ada modul pembelajaran
                                    yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document
                .querySelectorAll('form[data-confirm]')
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!window.confirm(form.dataset.confirm)) {
                            event.preventDefault();
                        }
                    });
                });
        });
    </script>
@endsection
