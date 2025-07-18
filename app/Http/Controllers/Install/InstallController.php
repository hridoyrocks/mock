<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;

class InstallController extends Controller
{
    public function welcome()
    {
        return view('installer.welcome');
    }

    public function requirements()
    {
        $phpVersion = phpversion();
        $requirements = [
            'php' => [
                'current' => $phpVersion,
                'required' => '8.2.0',
                'satisfied' => version_compare($phpVersion, '8.2.0', '>=')
            ],
            'extensions' => [
                'bcmath' => extension_loaded('bcmath'),
                'ctype' => extension_loaded('ctype'),
                'curl' => extension_loaded('curl'),
                'dom' => extension_loaded('dom'),
                'fileinfo' => extension_loaded('fileinfo'),
                'json' => extension_loaded('json'),
                'mbstring' => extension_loaded('mbstring'),
                'openssl' => extension_loaded('openssl'),
                'pdo' => extension_loaded('pdo'),
                'pdo_mysql' => extension_loaded('pdo_mysql'),
                'tokenizer' => extension_loaded('tokenizer'),
                'xml' => extension_loaded('xml'),
                'zip' => extension_loaded('zip'),
                'gd' => extension_loaded('gd'),
            ]
        ];

        $satisfied = $requirements['php']['satisfied'] && !in_array(false, $requirements['extensions']);

        return view('installer.requirements', compact('requirements', 'satisfied'));
    }

    public function permissions()
    {
        $permissions = [
            'storage/app' => [
                'path' => storage_path('app'),
                'required' => '775',
                'current' => $this->getPermission(storage_path('app')),
                'isWritable' => is_writable(storage_path('app'))
            ],
            'storage/framework' => [
                'path' => storage_path('framework'),
                'required' => '775',
                'current' => $this->getPermission(storage_path('framework')),
                'isWritable' => is_writable(storage_path('framework'))
            ],
            'storage/logs' => [
                'path' => storage_path('logs'),
                'required' => '775',
                'current' => $this->getPermission(storage_path('logs')),
                'isWritable' => is_writable(storage_path('logs'))
            ],
            'bootstrap/cache' => [
                'path' => base_path('bootstrap/cache'),
                'required' => '775',
                'current' => $this->getPermission(base_path('bootstrap/cache')),
                'isWritable' => is_writable(base_path('bootstrap/cache'))
            ],
            'public/uploads' => [
                'path' => public_path('uploads'),
                'required' => '775',
                'current' => $this->getPermission(public_path('uploads')),
                'isWritable' => is_writable(public_path('uploads'))
            ],
        ];

        // Create uploads directory if it doesn't exist
        if (!File::exists(public_path('uploads'))) {
            File::makeDirectory(public_path('uploads'), 0775, true);
        }

        $satisfied = !in_array(false, array_column($permissions, 'isWritable'));

        return view('installer.permissions', compact('permissions', 'satisfied'));
    }

    public function database()
    {
        return view('installer.database');
    }

    public function databaseSave(Request $request)
    {
        $request->validate([
            'database_hostname' => 'required',
            'database_port' => 'required|numeric',
            'database_name' => 'required',
            'database_username' => 'required',
            'database_password' => 'nullable',
        ]);

        try {
            // Test database connection
            $connection = new \PDO(
                "mysql:host={$request->database_hostname};port={$request->database_port};dbname={$request->database_name}",
                $request->database_username,
                $request->database_password,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );

            // Update .env file
            $this->updateEnvFile([
                'DB_HOST' => $request->database_hostname,
                'DB_PORT' => $request->database_port,
                'DB_DATABASE' => $request->database_name,
                'DB_USERNAME' => $request->database_username,
                'DB_PASSWORD' => $request->database_password,
            ]);

            // Clear config cache
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            return redirect()->route('installer.migration')->with('success', 'Database configured successfully!');

        } catch (Exception $e) {
            return back()->withErrors(['database' => 'Could not connect to database. Please check your settings.'])->withInput();
        }
    }

    public function migration()
    {
        return view('installer.migration');
    }

    public function runMigration()
    {
        try {
            // Run migrations
            Artisan::call('migrate:fresh', ['--force' => true]);
            
            // Run seeders for initial data
            $this->seedInitialData();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function admin()
    {
        return view('installer.admin');
    }

    public function adminSave(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            // Create admin user
            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => true,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]);

            return redirect()->route('installer.final');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to create admin account.'])->withInput();
        }
    }

    public function final()
    {
        // Create installed file
        File::put(storage_path('installed'), 'IELTS Mock Platform Installed on ' . date('Y-m-d H:i:s'));
        
        // Update .env
        $this->updateEnvFile([
            'APP_INSTALLED' => 'true',
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
        ]);

        // Generate app key if not exists
        if (empty(env('APP_KEY'))) {
            Artisan::call('key:generate');
        }

        // Clear all caches
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return view('installer.final');
    }

    private function getPermission($path)
    {
        return substr(sprintf('%o', fileperms($path)), -4);
    }

    private function updateEnvFile($data)
    {
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        File::put($envPath, $envContent);
    }

    private function seedInitialData()
    {
        // Create test sections
        DB::table('test_sections')->insert([
            ['name' => 'Listening', 'slug' => 'listening', 'description' => 'IELTS Listening Test', 'duration_minutes' => 30, 'is_active' => true],
            ['name' => 'Reading', 'slug' => 'reading', 'description' => 'IELTS Reading Test', 'duration_minutes' => 60, 'is_active' => true],
            ['name' => 'Writing', 'slug' => 'writing', 'description' => 'IELTS Writing Test', 'duration_minutes' => 60, 'is_active' => true],
            ['name' => 'Speaking', 'slug' => 'speaking', 'description' => 'IELTS Speaking Test', 'duration_minutes' => 15, 'is_active' => true],
        ]);

        // Create subscription plans
        DB::table('subscription_plans')->insert([
            [
                'name' => 'Free Plan',
                'slug' => 'free',
                'price' => 0,
                'currency' => 'BDT',
                'duration_days' => 0,
                'description' => 'Basic access to platform',
                'is_active' => true,
                'is_popular' => false,
                'is_free' => true,
                'display_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Basic Plan',
                'slug' => 'basic',
                'price' => 500,
                'currency' => 'BDT',
                'duration_days' => 30,
                'description' => 'Access to all mock tests',
                'is_active' => true,
                'is_popular' => false,
                'is_free' => false,
                'display_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium Plan',
                'slug' => 'premium',
                'price' => 1500,
                'currency' => 'BDT',
                'duration_days' => 90,
                'description' => 'All features with AI evaluation',
                'is_active' => true,
                'is_popular' => true,
                'is_free' => false,
                'display_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create subscription features
        DB::table('subscription_features')->insert([
            ['key' => 'mock_test', 'name' => 'Mock Tests', 'description' => 'Access to mock tests', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'ai_writing_evaluation', 'name' => 'AI Writing Evaluation', 'description' => 'AI-powered writing evaluation', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'ai_speaking_evaluation', 'name' => 'AI Speaking Evaluation', 'description' => 'AI-powered speaking evaluation', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'detailed_analytics', 'name' => 'Detailed Analytics', 'description' => 'Advanced performance analytics', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'unlimited_attempts', 'name' => 'Unlimited Attempts', 'description' => 'Unlimited test attempts', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Create maintenance mode entry
        DB::table('maintenance_mode')->insert([
            'is_enabled' => false,
            'message' => 'We are currently performing scheduled maintenance. Please check back later.',
            'allowed_ips' => json_encode([]),
            'starts_at' => null,
            'ends_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
