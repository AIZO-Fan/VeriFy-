@if(!($scanHistoryConnected ?? false))
    @include('activity-history.partials.empty-state', [
        'label' => 'INTEGRASI DATA',
        'title' => 'Riwayat pemeriksaan belum terhubung',
        'description' => 'Halaman ini sudah siap. Data pemeriksaan perlu disambungkan dari controller Settings.',
        'url' => route('detect'),
        'button' => 'Buka Detect Scam',
    ])
@elseif($scanHistories->count() > 0)
    <div class="verify-activity-list">
        @foreach($scanHistories as $scan)
            @php
                $score = $scan->score ?? 0;
                $flags = collect($scan->flags ?? []);

                $badgeClass = match(true) {
                    $score >= 60 => 'is-danger',
                    $score > 0 => 'is-warning',
                    default => 'is-success',
                };

                $badgeLabel = match(true) {
                    $score >= 60 => 'Risiko Tinggi',
                    $score >= 30 => 'Perlu Waspada',
                    $score > 0 => 'Perlu Diperiksa',
                    default => 'Aman',
                };
            @endphp

            <article class="verify-activity-item">
                <div class="verify-activity-item-main">
                    <div class="verify-activity-item-top">
                        <h3>
                            {{ \Illuminate\Support\Str::limit($scan->content, 85) }}
                        </h3>

                        <span class="verify-activity-badge {{ $badgeClass }}">
                            {{ $badgeLabel }} · Skor {{ $score }}/100
                        </span>
                    </div>

                    @if($flags->isNotEmpty())
                        <ul class="verify-activity-tags">
                            @foreach($flags->take(3) as $flag)
                                <li>{{ $flag }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="verify-activity-meta">
                        <span>
                            {{ $scan->created_at?->format('d M Y, H:i') ?? 'Waktu tidak tersedia' }}
                        </span>

                        <span>
                            {{ $scan->mode === 'url' ? 'Tautan URL' : 'Teks pesan' }}
                        </span>
                    </div>
                </div>

                <a
                    class="verify-activity-action"
                    href="{{ route('detect') }}"
                    title="Buka halaman pemeriksaan"
                >
                    Periksa lagi
                    <span aria-hidden="true">→</span>
                </a>
            </article>
        @endforeach
    </div>

    @if($scanHistories instanceof \Illuminate\Contracts\Pagination\Paginator)
        <div class="verify-activity-pagination">
            {{ $scanHistories->appends(['category' => 'detect'])->links() }}
        </div>
    @endif

    <p class="verify-activity-note">
        Hasil pemeriksaan awal bukan jaminan keamanan.
    </p>
@else
    @include('activity-history.partials.empty-state', [
        'label' => 'BELUM ADA AKTIVITAS',
        'title' => 'Belum ada riwayat pemeriksaan',
        'description' => 'Pemeriksaan yang tersimpan di akunmu akan ditampilkan di sini.',
        'url' => route('detect'),
        'button' => 'Mulai pemeriksaan',
    ])
@endif