<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'target_audience',
        'action_button_text',
        'action_button_url',
        'is_active',
        'is_dismissible',
        'start_date',
        'end_date',
        'priority',
        'image_url'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_dismissible' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the dismissals for the announcement.
     */
    public function dismissals(): HasMany
    {
        return $this->hasMany(AnnouncementDismissal::class);
    }

    /**
     * Check if the announcement is currently active
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Check if the announcement has been dismissed by a user
     */
    public function isDismissedByUser($userId): bool
    {
        return $this->dismissals()->where('user_id', $userId)->exists();
    }

    /**
     * Scope to get active announcements
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Scope to get announcements for students
     */
    public function scopeForStudents($query)
    {
        return $query->whereIn('target_audience', ['all', 'students']);
    }

    /**
     * Get the type badge color
     */
    public function getTypeBadgeColorAttribute(): string
    {
        return match($this->type) {
            'warning' => 'yellow',
            'success' => 'green',
            'promotion' => 'purple',
            default => 'blue',
        };
    }

    /**
     * Get the type icon
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'warning' => 'exclamation-triangle',
            'success' => 'check-circle',
            'promotion' => 'gift',
            default => 'information-circle',
        };
    }
}
