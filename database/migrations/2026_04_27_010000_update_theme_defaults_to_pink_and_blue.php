<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')
            ->where('theme_preference', 'ember')
            ->update(['theme_preference' => 'light']);

        DB::table('users')
            ->whereNull('theme_preference')
            ->update(['theme_preference' => 'light']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('theme_preference')->default('light')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('theme_preference')->default('system')->change();
        });
    }
};
