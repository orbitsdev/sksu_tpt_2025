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
        Schema::create('personal_information', function (Blueprint $table) {
            $table->id();
             $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            // Identity
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix', 10)->nullable();
            $table->string('nickname')->nullable();

            // Demographics
            $table->enum('sex', ['Male', 'Female', 'Other'])->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();

            // Contact
            $table->string('email')->nullable()->unique();
            $table->string('contact_number', 20)->nullable();

            // Address
            $table->string('house_no')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('municipality')->nullable();
            $table->string('province')->nullable();
            $table->string('region')->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->timestamps();

            // Index for performance
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_information');
    }
};
