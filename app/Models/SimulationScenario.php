<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimulationScenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'slug',
        'title',
        'description',
        'difficulty',
        'is_active',
        'version',
        'story'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'story' => 'array',
    ];
}
