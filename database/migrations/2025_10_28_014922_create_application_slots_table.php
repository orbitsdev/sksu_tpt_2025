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
        Schema::create('application_slots', function (Blueprint $table) {
             $table->id();

            // Relations
            $table->foreignId('application_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('examination_slot_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('examination_room_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Seating
            $table->unsignedInteger('seat_number')
                ->nullable()
                ->comment('Seat number inside room');

            // Constraints
            $table->unique(
                ['application_id', 'examination_slot_id'],
                'unique_app_slot'
            );

            // Indexing for speed
            $table->index('examination_slot_id');
            $table->index('examination_room_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_slots');
    }
};
