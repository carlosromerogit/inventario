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
        Schema::create('drives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('computer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('drive_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('brand_model_id')->constrained()->restrictOnDelete();

            $table->integer('capacity_value'); // Ej: 512, 1, 2
            $table->string('capacity_unit');   // Ej: 'GB', 'TB', 'MB'
            $table->integer('capacity_in_mb'); // Ej: 512000, 1024000 (Para ordenar y filtrar)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drives');
    }
};
