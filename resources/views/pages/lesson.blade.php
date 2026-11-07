@extends('layouts.app')
@section('title',$module['title'])
@section('content')<section class="container lesson"><a class="back-link" href="{{ route('modules') }}">← Semua modul</a>
    <article class="lesson-card {{ $module['color'] }}"><span class="eyebrow">MODUL 0{{ $module['id'] }}</span>
        <h1>{{ $module['title'] }}</h1>
        <p>{{ $module['description'] }}</p>
        <ol class="lesson-list">@foreach($module['lessons'] as $text)<li>{{ $text }}</li>@endforeach</ol><a class="button" href="{{ route('simulator',['topic'=>$module['id']]) }}">Latih pemahamanmu →</a>
    </article>
    <p class="fine-print">Materi pengantar edukatif; bukan nasihat keamanan atau keuangan profesional.</p>
</section>@endsection