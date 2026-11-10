@if(!($simulatorHistoryConnected ?? false))
    @include('activity-history.partials.empty-state', [
        'label' => 'INTEGRASI DATA',
        'title' => 'Riwayat latihan belum terhubung',
        'description' => 'Penyimpanan hasil latihan perlu disambungkan ke halaman ini.',
        'url' => route('simulator'),
        'button' => 'Buka simulator',
    ])
@elseif($simulationAttempts->count() > 0)
    <div class="verify-activity-list">
        @foreach($simulationAttempts as $attempt)
            @php
                $scenario = $attempt->scenario;

                $hasScore = $attempt->score !== null
                    && $attempt->max_score !== null
                    && (float) $attempt->max_score > 0;
            @endphp

            <article class="verify-activity-item">
                <div class="verify-activity-item-main">
                    <div class="verify-activity-item-top">
                        <h3>
                            {{ $scenario?->title ?? 'Skenario tidak tersedia' }}
                        </h3>

                        @if($hasScore)
                            <span class="verify-activity-badge is-neutral">
                                Skor {{ $attempt->score }}
                                / {{ $attempt->max_score }}
                            </span>
                        @else
                            <span class="verify-activity-badge is-neutral">
                                Percobaan tersimpan
                            </span>
                        @endif
                    </div>

                    <div class="verify-activity-meta">
                        <span>
                            {{ $attempt->created_at?->format('d M Y, H:i')
                                ?? 'Waktu tidak tersedia' }}
                        </span>

                        @if($attempt->scenario_version !== null)
                            <span>
                                Versi skenario {{ $attempt->scenario_version }}
                            </span>
                        @endif

                        <span>Scam Simulator</span>
                    </div>
                </div>

                <a
                    class="verify-activity-action"
                    href="{{ route('simulator') }}"
                >
                    Buka simulator
                    <span aria-hidden="true">→</span>
                </a>
            </article>
        @endforeach
    </div>

    @if($simulationAttempts instanceof \Illuminate\Contracts\Pagination\Paginator)
        <div class="verify-activity-pagination">
            {{ $simulationAttempts
                ->appends(['category' => 'simulator'])
                ->links() }}
        </div>
    @endif

    <p class="verify-activity-note">
        Skor menunjukkan hasil percobaan pada skenario terkait,
        bukan jaminan kemampuan menghadapi seluruh penipuan digital.
    </p>
@else
    @include('activity-history.partials.empty-state', [
        'label' => 'BELUM ADA AKTIVITAS',
        'title' => 'Belum ada percobaan tersimpan',
        'description' => 'Hasil latihan yang berhasil disimpan ke akunmu akan ditampilkan di sini.',
        'url' => route('simulator'),
        'button' => 'Mulai latihan',
    ])
@endif