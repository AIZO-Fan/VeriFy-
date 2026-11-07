@extends('layouts.app')
@section('title','Jelajah Dunia Digital dengan Aman')
@section('content')
<section class="hero">
    <div class="hero-copy"><span class="eyebrow">KENALI RISIKONYA, LINDUNGI DIRIMU</span>
        <h1>Jelajah Dunia Digital<br>dengan <em>Aman!</em></h1>
        <p>SiberGuard membantu kamu mengenali penipuan,<br>melindungi data, dan lebih percaya diri di dunia digital.</p>
        <div class="actions"><a class="button yellow" href="{{ route('modules') }}">Mulai Belajar <span>→</span></a><a class="button white" href="{{ route('detect') }}">Deteksi Scam →</a></div>
        <div class="hero-meta"><span>6 modul pembelajaran</span><span>6 latihan interaktif</span></div>
    </div>
    <div class="hero-art" aria-label="Ilustrasi perlindungan digital">
        <div class="float-card card-a"><x-icon name="lock" /><span>Data terlindungi</span></div>
        <div class="phone">
            <div class="notch"></div>
            <div class="phone-body">
                <div class="phone-shield"><x-icon /></div>
                <h3>Stay safe.<br>Stay smart.</h3>
                <p>Keamanan digital<br>dimulai dari kamu.</p>
                <div class="phone-check">✓ Kenali • Cek • Lindungi</div>
            </div>
        </div>
        <div class="float-card card-b"><x-icon name="link" /><span>Periksa tautan</span></div>
        <div class="float-card card-c"><x-icon /><span>Kenali penipuan</span></div>
    </div>
</section>
@endsection