<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->boolean('is_free')->default(false)->after('slug');
        });
        
        // Update existing free plan
        \DB::table('subscription_plans')
            ->where('slug', 'free')
            ->orWhere('price', 0)
            ->update(['is_free' => true]);
    }

    public function down()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('is_free');
        });
    }
};