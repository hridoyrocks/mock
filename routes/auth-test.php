<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Create a test user
Route::get('/create-test-user', function() {
    try {
        // Check if test user exists
        $existingUser = User::where('email', 'test@example.com')->first();
        
        if ($existingUser) {
            // Update password
            $existingUser->password = Hash::make('password');
            $existingUser->email_verified_at = now();
            $existingUser->save();
            
            return response()->json([
                'status' => 'updated',
                'message' => 'Test user updated',
                'credentials' => [
                    'email' => 'test@example.com',
                    'password' => 'password'
                ]
            ]);
        }
        
        // Create new test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'phone_number' => '1234567890',
            'country_code' => 'BD',
            'country_name' => 'Bangladesh',
        ]);
        
        return response()->json([
            'status' => 'created',
            'message' => 'Test user created successfully',
            'credentials' => [
                'email' => 'test@example.com',
                'password' => 'password'
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Simple login test without checkboxes
Route::get('/test-simple-login', function() {
    $credentials = [
        'email' => 'test@example.com',
        'password' => 'password'
    ];
    
    if (Auth::attempt($credentials)) {
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'user' => Auth::user()
        ]);
    } else {
        return response()->json([
            'status' => 'failed',
            'message' => 'Login failed'
        ]);
    }
});

// Check current auth status
Route::get('/check-auth', function() {
    return response()->json([
        'authenticated' => Auth::check(),
        'user' => Auth::user(),
        'session_id' => session()->getId(),
        'session_data' => session()->all()
    ]);
});