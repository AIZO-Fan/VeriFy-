@extends('layouts.app')

@section('title', $module->title)

@section('content')
<section class="container lesson-container">

    <a href="{{ route('modules') }}" class="back-link">
        ‹ Kembali ke Modul Pembelajaran
    </a>

    {{-- HEADER MODUL --}}
    <div class="welcome-section">
        <div>
            <span>MODUL {{ str_pad($module->id, 2, '0', STR_PAD_LEFT) }}</span>
            <h2>{{ $module->title }}</h2>
            <p>{{ $module->description }}</p>
        </div>
    </div>

    {{-- ISI MATERI DARI DATABASE --}}
    <main class="lesson-content">
        @forelse($module->sections as $section)
        <article class="lesson-card type-{{ $section->type }}">
            <h3>
                @if($section->type === 'example') 💡
                @elseif($section->type === 'summary') 📌
                @else 📖
                @endif
                {{ $section->title }}
            </h3>

            <div class="lesson-body">
                {!! $section->content !!}
            </div>
        </article>
        @empty
        <div class="lesson-card">
            <p>Materi untuk modul ini belum tersedia.</p>
        </div>
        @endforelse
    </main>

    {{-- BOX LATIHAN SIMULASI --}}
    <div class="practice-box">
        <div>
            <h4>Uji Pemahamanmu</h4>
            <p>Coba instruksi dan skenario interaktif untuk melatih responmu.</p>
        </div>

        @if($practice['available'])
        <a href="{{ $practice['url'] }}" class="button">
            Mulai Latihan ›
        </a>
        @else
        <span class="button-disabled">Latihan belum tersedia</span>
        @endif
    </div>

</section>
@endsection