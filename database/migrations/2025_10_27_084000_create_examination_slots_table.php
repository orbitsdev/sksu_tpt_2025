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
        Schema::create('examination_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examination_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_center_id')->constrained()->onDelete('cascade');
            $table->string('building_name')->nullable();
            $table->date('date_of_exam');
            $table->unsignedBigInteger('total_examinees')->default(0)->comment('Total examinees for this slot');
            $table->unsignedBigInteger('number_of_rooms')->default(0)->comment('Total available rooms for examinees');
            $table->boolean('is_active')->default(true)->comment('Indicates if the slot is active');
            $table->timestamps();

            // Indexes for performance
            $table->index('date_of_exam');
            $table->index('is_active');
            $table->index(['examination_id', 'date_of_exam']); // Composite index for common queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examination_slots');
    }
};
