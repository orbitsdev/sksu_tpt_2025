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
        Schema::create('application_information', function (Blueprint $table) {
           $table->id();

            $table->foreignId('application_id')
                ->constrained()
                ->onDelete('cascade');

            // basic info
            $table->string('type')->comment('Freshmen or Transferee');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('extension')->nullable();

            // address
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();

            // contact
            $table->string('contact_number', 20)->nullable();

            // demographics
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('tribe')->nullable();
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('sex')->nullable();

            // photo path
            $table->string('photo')->nullable();

            // education history
            $table->string('school_graduated')->nullable();
            $table->string('year_graduated', 10)->nullable();
            $table->string('school_last_attended')->nullable();
            $table->string('year_last_attended', 10)->nullable();
            $table->string('previous_school_address')->nullable();
            $table->string('track_and_strand_taken')->nullable();

            $table->timestamps();

            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_information');
    }
};
