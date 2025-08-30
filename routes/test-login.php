<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Test route to check login functionality
Route::get('/test-login-setup', function() {
    $results = [];
    
    // Check if trusted_devices table exists
    $results['trusted_devices_table'] = Schema::hasTable('trusted_devices') ? 'EXISTS' : 'NOT EXISTS';
    
    // Check if sessions table exists
    $results['sessions_table'] = Schema::hasTable('sessions') ? 'EXISTS' : 'NOT EXISTS';
    
    // Check if users table has remember_token column
    $results['remember_token_column'] = Schema::hasColumn('users', 'remember_token') ? 'EXISTS' : 'NOT EXISTS';
    
    // Check sample user
    $user = DB::table('users')->first();
    if ($user) {
        $results['sample_user'] = [
            'email' => $user->email,
            'has_password' => !empty($user->password),
            'email_verified' => !is_null($user->email_verified_at)
        ];
    }
    
    // Check session config
    $results['session_config'] = [
        'driver' => config('session.driver'),
        'lifetime' => config('session.lifetime'),
        'remember' => config('session.remember', 'NOT SET')
    ];
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});

// Quick fix route to add missing columns/tables
Route::get('/fix-login-tables', function() {
    $fixed = [];
    
    // Create trusted_devices table if not exists
    if (!Schema::hasTable('trusted_devices')) {
        Schema::create('trusted_devices', function ($table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_token')->unique();
            $table->string('ip_address');
            $table->text('user_agent');
            $table->timestamp('trusted_until');
            $table->timestamps();
            
            $table->index(['user_id', 'device_token']);
            $table->index('trusted_until');
        });
        $fixed[] = 'Created trusted_devices table';
    }
    
    // Add remember_token to users if missing
    if (!Schema::hasColumn('users', 'remember_token')) {
        Schema::table('users', function ($table) {
            $table->rememberToken();
        });
        $fixed[] = 'Added remember_token to users table';
    }
    
    // Create sessions table if not exists and driver is database
    if (config('session.driver') === 'database' && !Schema::hasTable('sessions')) {
        Schema::create('sessions', function ($table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity')->index();
        });
        $fixed[] = 'Created sessions table';
    }
    
    return response()->json([
        'status' => 'success',
        'fixed' => $fixed
    ], 200, [], JSON_PRETTY_PRINT);
});