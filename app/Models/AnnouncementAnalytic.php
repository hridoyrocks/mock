<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnouncementAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'user_id',
        'action_type',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the announcement that owns the analytic.
     */
    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class);
    }

    /**
     * Get the user that owns the analytic.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include viewed actions.
     */
    public function scopeViewed($query)
    {
        return $query->where('action_type', 'viewed');
    }

    /**
     * Scope a query to only include clicked actions.
     */
    public function scopeClicked($query)
    {
        return $query->where('action_type', 'clicked');
    }

    /**
     * Scope a query to only include dismissed actions.
     */
    public function scopeDismissed($query)
    {
        return $query->where('action_type', 'dismissed');
    }
}
