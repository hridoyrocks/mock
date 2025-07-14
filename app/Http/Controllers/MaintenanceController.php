<?php
// app/Http/Controllers/MaintenanceController.php

namespace App\Http\Controllers;

use App\Models\MaintenanceMode;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenance = MaintenanceMode::current();
        
        if (!$maintenance) {
            return redirect()->route('student.dashboard');
        }

        return view('maintenance', compact('maintenance'));
    }
}