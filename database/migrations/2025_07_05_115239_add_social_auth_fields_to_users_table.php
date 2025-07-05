<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->unique()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('phone_number');
            $table->string('google_id')->nullable()->unique()->after('password');
            $table->string('facebook_id')->nullable()->unique()->after('google_id');
            $table->string('avatar_url')->nullable()->after('facebook_id');
            $table->enum('login_method', ['email', 'google', 'facebook'])->default('email')->after('avatar_url');
            $table->string('country_code', 2)->nullable()->after('login_method');
            $table->string('country_name')->nullable()->after('country_code');
            $table->string('city')->nullable()->after('country_name');
            $table->string('timezone')->nullable()->after('city');
            $table->string('currency', 3)->nullable()->after('timezone');
            $table->boolean('is_social_signup')->default(false)->after('currency');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number', 'phone_verified_at', 'google_id', 'facebook_id',
                'avatar_url', 'login_method', 'country_code', 'country_name',
                'city', 'timezone', 'currency', 'is_social_signup'
            ]);
        });
    }
};