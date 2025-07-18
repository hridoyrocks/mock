<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementDismissal;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Get active announcements for the current student
     */
    public function getActiveAnnouncements()
    {
        $user = auth()->user();
        
        $announcements = Announcement::active()
            ->forStudents()
            ->orderBy('priority', 'desc')
            ->get()
            ->filter(function ($announcement) use ($user) {
                return !$announcement->isDismissedByUser($user->id);
            })
            ->values();

        return response()->json([
            'announcements' => $announcements
        ]);
    }

    /**
     * Dismiss an announcement
     */
    public function dismiss(Request $request, Announcement $announcement)
    {
        if (!$announcement->is_dismissible) {
            return response()->json([
                'error' => 'This announcement cannot be dismissed'
            ], 400);
        }

        AnnouncementDismissal::firstOrCreate([
            'user_id' => auth()->id(),
            'announcement_id' => $announcement->id,
        ], [
            'dismissed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Announcement dismissed successfully'
        ]);
    }
}
