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
        Schema::create('equipment_loans', function (Blueprint $table) {
            $table->id();
            $table->morphs('loanable'); 
            
            $table->text('reason'); 
            $table->timestamp('loaned_at'); 
            $table->timestamp('expected_return_at')->nullable(); 
            $table->timestamp('returned_at')->nullable(); 

            $table->string('borrower_first_name');
            $table->string('borrower_last_name');
            $table->string('borrower_company')->nullable();
            $table->string('borrower_phone')->nullable();
            $table->string('borrower_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_loans');
    }
};
