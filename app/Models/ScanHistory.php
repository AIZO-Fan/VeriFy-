<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScanHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'mode',
        'score',
        'status',
        'flags',
        'urls_checked',
        'threat_urls',
    ];

    protected function casts(): array
    {
        return [
            'flags'       => 'array',
            'threat_urls' => 'array',
            'score'       => 'integer',
            'urls_checked'=> 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Dapatkan level risiko standar untuk badge tampilan: danger, warning, safe.
     */
    public function getRiskLevelAttribute(): string
    {
        $status = strtolower($this->status ?? '');
        $score = (int) ($this->score ?? 0);

        if ($score >= 60 || str_contains($status, 'bahaya') || str_contains($status, 'kewaspadaan')) {
            return 'danger';
        }

        if ($score >= 20 || str_contains($status, 'waspada') || str_contains($status, 'lebih lanjut')) {
            return 'warning';
        }

        return 'safe';
    }

    /**
     * Dapatkan label ringkas status untuk badge.
     */
    public function getRiskLabelAttribute(): string
    {
        return match ($this->risk_level) {
            'danger'  => 'Bahaya',
            'warning' => 'Waspada',
            default   => 'Aman',
        };
    }
}
