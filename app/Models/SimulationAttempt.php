<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimulationAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scenario_id',
        'decision_path',
        'score',
        'max_score',
        'scenario_version',
    ];

    // Otomatis mengubah JSON dari database menjadi Array di PHP
    protected $casts = [
        'decision_path' => 'array',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel simulation_scenarios
    public function scenario()
    {
        return $this->belongsTo(SimulationScenario::class, 'scenario_id');
    }
}
