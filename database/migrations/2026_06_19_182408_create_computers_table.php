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
        Schema::create('computers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_model_id')->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->string('serial')->unique();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->string('processor')->nullable();
            $table->string('ram')->nullable();
            $table->string('hostname')->nullable()->unique();;
            $table->string('fixed_asset')->nullable()->unique();
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('operating_system_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->enum('status', ['stock', 'assigned', 'faulty', 'obsolete'])->default('stock');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computers');
    }
};
