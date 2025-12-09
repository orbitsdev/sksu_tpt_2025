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
        Schema::create('test_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->string('name')->comment('Name of the test center');
            $table->text('address')->nullable()->comment('Address of the test center');
            $table->boolean('is_active')->default(true)->comment('Whether the test center is active');
            $table->timestamps();
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_centers');
    }
};
