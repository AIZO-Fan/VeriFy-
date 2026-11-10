<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'color',
        'icon',
        'lessons',
        'order',
        'is_active',
    ];

    protected $casts = [
        'lessons' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
}

