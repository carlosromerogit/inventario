<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warrantables', function (Blueprint $table) {
        $table->id();

        $table->foreignId('warranty_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->morphs('warrantable');

        $table->unique([
            'warranty_id',
            'warrantable_type',
            'warrantable_id'
        ]);

        $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('warrantyables');
    }
};