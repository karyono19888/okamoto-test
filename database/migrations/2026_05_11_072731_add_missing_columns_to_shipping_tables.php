<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('level_cases', function (Blueprint $table) {
            $table->string('o_f')->nullable();
        });
        Schema::table('level_parts', function (Blueprint $table) {
            $table->string('ruibe')->nullable();
            $table->string('fta_code')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('level_cases', function (Blueprint $table) {
            $table->dropColumn('o_f');
        });
        Schema::table('level_parts', function (Blueprint $table) {
            $table->dropColumn(['ruibe', 'fta_code']);
        });
    }
};
