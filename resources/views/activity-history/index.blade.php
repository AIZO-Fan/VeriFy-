@extends('layouts.app')

@section('title', 'Riwayat Aktivitas')

@section('content')
    @php
        $categories = [
            'detect' => [
                'label' => 'Detect Scam',
                'description' => 'Pemeriksaan pesan dan tautan yang pernah kamu lakukan.',
            ],
            'modules' => [
                'label' => 'Modules',
                'description' => 'Status pembelajaran dan modul yang ingin kamu lanjutkan.',
            ],
            'simulator' => [
                'label' => 'Scam Simulator',
                'description' => 'Percobaan latihan dan hasil keputusanmu.',
            ],
        ];
    @endphp

    <section class="verify-activity-page">
        <div class="verify-activity-container">

            <a class="verify-activity-back" href="{{ route('settings') }}">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5" />
                    <path d="m12 19-7-7 7-7" />
                </svg>

                <span>Kembali ke pengaturan</span>
            </a>

            <header class="verify-activity-heading">
                <span class="verify-activity-eyebrow">
                    <span aria-hidden="true"></span>
                    PERJALANANMU DI VERIFY
                </span>

                <h1>Riwayat <span>Aktivitas.</span></h1>

                <p>
                    Lihat kembali pemeriksaan, lanjutkan pembelajaran,
                    dan temukan hasil latihanmu.
                </p>
            </header>

            <div class="verify-activity-panel">

                <nav class="verify-activity-tabs" aria-label="Kategori riwayat aktivitas">
                    @foreach ($categories as $key => $item)
                        <a href="{{ route('activity-history.index', ['category' => $key]) }}"
                            @if ($category === $key) aria-current="page" @endif>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="verify-activity-panel-body">
                    <header class="verify-activity-section-heading">
                        <h2>{{ $categories[$category]['label'] }}</h2>

                        <p>{{ $categories[$category]['description'] }}</p>
                    </header>

                    @if ($category === 'detect')
                        @include('activity-history.partials.detect')
                    @elseif($category === 'modules')
                        @include('activity-history.partials.modules')
                    @else
                        @include('activity-history.partials.simulator')
                    @endif
                </div>

            </div>

        </div>
    </section>
@endsection
