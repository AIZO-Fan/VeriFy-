@extends('layouts.app')
@section('title','Scam Simulator')
@section('content')<section class="container simulator-section">
    <h1 class="ribbon centered"><x-icon name="brain" /> Scam Simulator</h1>
    <p class="section-description center">Belajar mengambil keputusan lewat skenario sehari-hari.</p>
    <div class="carousel-wrap"><button class="carousel-arrow prev" aria-label="Simulator sebelumnya">‹</button>
        <div class="simulator-track">@foreach($modules as $module)<article class="sim-card {{ $module['color'] }}"><span class="badge">Simulator {{ str_pad($module['id'],2,'0',STR_PAD_LEFT) }}</span>
                <div class="module-icon"><x-icon :name="$module['icon']" /></div>
                <h2>{{ $module['title'] }}</h2>
                <div class="sim-art">
                    <div class="art-orbit"></div><x-icon :name="$module['icon']" /><span class="art-spark">+</span>
                    <div class="art-lines"><i></i><i></i><i></i></div>
                </div><button class="try-button" data-sim="{{ $module['id'] }}">Try Simulator <span>→</span></button>
            </article>@endforeach</div><button class="carousel-arrow next" aria-label="Simulator berikutnya">›</button>
    </div>
    <p class="fine-print center">Skenario latihan fiktif. Tidak ada pesan, tautan, atau transaksi nyata.</p>
</section>
<dialog id="quiz-dialog" aria-labelledby="quiz-title">
    <div class="quiz-top"><span class="eyebrow">LATIHAN INTERAKTIF</span><button class="close-dialog" aria-label="Tutup simulator">×</button></div>
    <h2 id="quiz-title"></h2>
    <div id="quiz-message"></div>
    <p>Apa tindakan paling tepat?</p>
    <div id="quiz-options"></div>
    <div id="quiz-feedback" aria-live="polite"></div><button class="button close-dialog">Selesai</button>
</dialog>@endsection