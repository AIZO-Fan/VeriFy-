@extends('layouts.app')
@section('title','Detect Scam')
@section('content')<div class="wave"></div>
<section class="detect-grid container">
    <div><span class="eyebrow">CEK SEBELUM PERCAYA</span>
        <h1 class="small-title">DETEKSI PENIPUAN</h1>
        <p>Masukkan pesan atau tautan yang mencurigakan untuk analisis awal.</p>
        <form method="POST" action="{{ route('analyze') }}">@csrf<div class="input-tabs"><label><input type="radio" name="mode" value="text" {{ old('mode',request('mode','text'))==='text'?'checked':'' }}> Teks Pesan</label><label><input type="radio" name="mode" value="url" {{ old('mode',request('mode'))==='url'?'checked':'' }}> URL</label></div><label class="sr-only" for="content">Pesan atau URL</label><textarea id="content" name="content" maxlength="5000" required placeholder="Tempelkan pesan atau tautan di sini...">{{ old('content',request('content')) }}</textarea>@error('content')<p class="error">{{ $message }}</p>@enderror @error('mode')<p class="error">{{ $message }}</p>@enderror<button class="button" type="submit">Periksa Sekarang <span>→</span></button></form>
        <p class="fine-print">Demo berbasis aturan. Jangan masukkan OTP, kata sandi, atau data pribadi. Tautan tidak dibuka atau dikunjungi oleh sistem.</p>
    </div>
    <aside class="analysis">
        <h2>▥ Hasil Analisis</h2>@if($result)<div class="notice {{ count($result['flags']) ? 'danger' : '' }}"><strong>{{ $result['status'] }}</strong><small>Hasil ini bukan kepastian bahwa pesan merupakan penipuan atau aman.</small></div>
        <div class="info-box">
            <h3>Indikator yang ditemukan</h3>
            <ul>@forelse($result['flags'] as $flag)<li>{{ $flag }}</li>@empty<li>Belum ditemukan indikator dari aturan demo.</li>@endforelse</ul>
        </div>@else<div class="empty-state"><x-icon name="report" />
            <h3>Siap membantu memeriksa</h3>
            <p>Hasil analisis awal akan muncul setelah kamu mengirim pesan atau URL.</p>
        </div>@endif<div class="info-box recommendation">
            <h3>Rekomendasi</h3>
            <p>Jangan klik tautan atau membagikan informasi pribadi sebelum melakukan verifikasi melalui saluran resmi.</p>
        </div>
    </aside>
</section>@endsection