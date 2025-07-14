<?php
// app/Models/MaintenanceMode.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceMode extends Model
{
    protected $fillable = [
        'is_active',
        'title',
        'message',
        'started_at',
        'expected_end_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'started_at' => 'datetime',
        'expected_end_at' => 'datetime',
    ];

    public static function isActive(): bool
    {
        return self::where('is_active', true)->exists();
    }

    public static function current()
    {
        return self::where('is_active', true)->latest()->first();
    }
}