<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnouncementDismissal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'announcement_id',
        'dismissed_at'
    ];

    protected $casts = [
        'dismissed_at' => 'datetime',
    ];

    /**
     * Get the user that dismissed the announcement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the announcement that was dismissed.
     */
    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class);
    }
}
