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
        Schema::create('examinations', function (Blueprint $table) {
            $table->id();
            //  Basic Info
            $table->string('title')->comment('Name or title of the examination');
            //  Schedule
            $table->date('start_date')->nullable()->comment('Exam opening or first day');
            $table->date('end_date')->nullable()->comment('Exam closing or last day');
            $table->boolean('is_published')->default(false)->comment('Indicates if the exam details are published');
            $table->boolean('is_application_open')->default(false)->comment('Determines if examinee registration is open');
            $table->boolean('show_result')->default(false)->comment('Determines if examinee result is displayed');
            $table->string('school_year')->nullable()->comment('Academic year, e.g. 2025-2026');
            $table->string('type')->nullable()->comment('Exam type, e.g. Entrance, Midterm, Final');
            $table->timestamps();

            // Indexes for performance
            $table->index('school_year');
            $table->index('type');
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examinations');
    }
};
