<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceMode;
use App\Models\User;
use App\Notifications\MaintenanceNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceModeController extends Controller
{
    public function index()
    {
        $currentMaintenance = MaintenanceMode::current();
        $maintenanceHistory = MaintenanceMode::latest()->paginate(10);
        
        return view('admin.maintenance.index', compact('currentMaintenance', 'maintenanceHistory'));
    }

      public function toggle(Request $request)
    {
        // Ensure this is a POST request
        if (!$request->isMethod('post')) {
            return redirect()->route('admin.maintenance.index')
                ->with('error', 'Invalid request method.');
        }

        $request->validate([
            'action' => 'required|in:enable,disable',
            'title' => 'required_if:action,enable|string|max:255',
            'message' => 'required_if:action,enable|string',
            'expected_end_at' => 'nullable|date|after:now',
        ]);

        try {
            DB::transaction(function () use ($request) {
                if ($request->action === 'enable') {
                    // Disable any existing maintenance
                    MaintenanceMode::where('is_active', true)->update(['is_active' => false]);

                    // Create new maintenance
                    $maintenance = MaintenanceMode::create([
                        'is_active' => true,
                        'title' => $request->title,
                        'message' => $request->message,
                        'started_at' => now(),
                        'expected_end_at' => $request->expected_end_at,
                    ]);
                } else {
                    // Disable maintenance
                    MaintenanceMode::where('is_active', true)->update(['is_active' => false]);
                }
            });

            return redirect()->route('admin.maintenance.index')->with('success', 
                $request->action === 'enable' 
                    ? 'Maintenance mode enabled successfully!' 
                    : 'Maintenance mode disabled successfully!'
            );
            
        } catch (\Exception $e) {
            return redirect()->route('admin.maintenance.index')
                ->with('error', 'Failed to toggle maintenance mode: ' . $e->getMessage());
        }
    }


    private function sendMaintenanceNotifications($maintenance, $type)
    {
         
        return;
        
         
        $users = User::where('is_admin', false)
            ->where('notify_maintenance', true)
            ->chunk(100, function ($users) use ($maintenance, $type) {
                foreach ($users as $user) {
                    try {
                        $user->notify(new MaintenanceNotification($maintenance, $type));
                        $user->update(['last_maintenance_notified_at' => now()]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to notify user ' . $user->id . ': ' . $e->getMessage());
                    }
                }
            });
        
    }
}