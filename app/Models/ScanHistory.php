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
}
