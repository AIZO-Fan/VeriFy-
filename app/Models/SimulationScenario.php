<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimulationScenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'topic_id',
        'slug',
        'title',
        'description',
        'difficulty',
        'status',
        'sort_order',
        'is_active',
        'version',
        'story'
    ];

    protected $casts = [
        'story' => 'array',
        'is_active' => 'boolean',
        'topic_id' => 'integer',
        'version' => 'integer',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function attempts()
    {
        return $this->hasMany(SimulationAttempt::class, 'scenario_id');
    }
}
