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
        Schema::create('level_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_case_id')->constrained('level_cases')->cascadeOnDelete();
            $table->string('parts_no')->nullable();
            $table->string('parts_name')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('unit_weight', 15, 6)->nullable();
            $table->decimal('net_weight', 15, 6)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_parts');
    }
};
