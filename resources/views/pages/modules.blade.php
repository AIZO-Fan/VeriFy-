@extends('layouts.app')
@section('title','Modul Pembelajaran')
@section('content')<section class="container module-section">
    <h1 class="ribbon"><x-icon name="report" /> Modul Pembelajaran</h1>
    <p class="section-description">Bangun kebiasaan digital yang lebih aman, satu modul setiap hari.</p>
    <div class="module-grid">@foreach($modules as $module)<article class="module-card {{ $module['color'] }}"><span class="badge">Modul {{ str_pad($module['id'],2,'0',STR_PAD_LEFT) }}</span>
            <div class="module-icon"><x-icon :name="$module['icon']" /></div>
            <h2>{{ $module['title'] }}</h2>
            <p>{{ $module['description'] }}</p><a class="mini-button" href="{{ route('lesson',$module['id']) }}">Explore Module →</a><span class="card-number">{{ str_pad($module['id'],2,'0',STR_PAD_LEFT) }}</span>
        </article>@endforeach</div>
</section>@endsection