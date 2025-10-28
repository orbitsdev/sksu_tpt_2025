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
            $table->foreignId('examination_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('campus_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('date_of_exam');
            $table->string('building_name')->nullable();
            $table->unsignedBigInteger('slots')->default(0)->comment('Total available slots for examinees');
            $table->unsignedBigInteger('number_of_rooms')->default(0)->comment('Total available rooms for examinees');
            $table->boolean('is_active')->default(true)->comment('Indicates if the slot is active');
            $table->timestamps();
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
