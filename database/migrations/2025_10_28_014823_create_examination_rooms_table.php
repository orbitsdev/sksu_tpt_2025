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
        Schema::create('examination_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examination_slot_id')->constrained()->onDelete('cascade');
            $table->string('room_number')->comment('e.g. Room 1, Room 2, Annex A');
            $table->unsignedInteger('capacity')->default(0);
            $table->unsignedInteger('occupied')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examination_rooms');
    }
};
 