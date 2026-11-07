@extends('layouts.app')
@section('title','How It Works')
@section('content')<section class="container process">
    <div class="section-heading"><span class="eyebrow">THE PROCESS</span>
        <h1>How It Works</h1>
        <p>Tiga langkah untuk lebih aman di dunia digital.</p>
    </div>
    <div class="process-tabs" role="tablist" aria-label="Langkah penggunaan">@foreach(['Belajar','Berlatih','Melindungi'] as $label)<button id="tab-{{ $loop->index }}" role="tab" aria-controls="step-{{ $loop->index }}" aria-selected="{{ $loop->first?'true':'false' }}" tabindex="{{ $loop->first?'0':'-1' }}" data-step="{{ $loop->index }}" class="{{ $loop->first?'selected':'' }}">0{{ $loop->iteration }} {{ $label }}</button>@endforeach</div>@foreach([['Kenali Tanda-Tanda Penipuan','Jelajahi enam modul pembelajaran untuk memahami modus scam, mengenali perilaku mencurigakan, dan mengetahui cara kerja penipuan digital.','6 Modules','Edukasi Scam','modules','blue','report'],['Uji Kemampuanmu','Ikuti simulasi interaktif untuk melatih kemampuan mengenali pesan penipuan, tautan mencurigakan, dan penawaran palsu.','6 Simulator','Interaktif','simulator','purple','brain'],['Lindungi Diri dan Ambil Tindakan','Periksa pesan mencurigakan, lindungi data pribadi, dan pelajari langkah yang dapat diambil saat menemukan dugaan penipuan.','Deteksi Tautan','Pencegahan Scam','detect','green','shield']] as $s)<article id="step-{{ $loop->index }}" role="tabpanel" aria-labelledby="tab-{{ $loop->index }}" class="step-panel {{ $s[5] }}" {{ $loop->first?'':'hidden' }}><span class="step-number">LANGKAH 0{{ $loop->iteration }}</span>
        <div class="step-icon"><x-icon :name="$s[6]" /></div>
        <h2>{{ $s[0] }}</h2>
        <p>{{ $s[1] }}</p>
        <div class="pills"><a href="{{ route($s[4]) }}">{{ $s[2] }} →</a><span>{{ $s[3] }}</span></div>
    </article>@endforeach
</section>@endsection