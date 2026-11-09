<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'sort_order'
    ];

    // Relasi ke bagian-bagian materi
    public function sections()
    {
        return $this->hasMany(ModuleSection::class)->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    // Relasi ke skenario simulator
    public function scenarios()
    {
        return $this->hasMany(SimulationScenario::class);
    }

    // Relasi khusus skenario yang sudah dipublikasikan
    public function publishedScenarios()
    {
        return $this->hasMany(SimulationScenario::class)
            ->where('status', 'published')
            ->orderBy('sort_order', 'asc');
    }
}
