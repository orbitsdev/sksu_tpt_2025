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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            // foreign keys
            $table->foreignId('examination_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // workflow status

            $table->unsignedTinyInteger('previous_step')->nullable()
                ->comment('Holds last completed step for tracking regressions.');
            $table->unsignedTinyInteger('current_step')->default(10)
                ->comment('Current step in the application workflow.');

            $table->string('step_description')->nullable()
                ->comment('User-friendly explanation of current step.');

            // examinee identifiers
            $table->string('examinee_number')->nullable()->unique();
            $table->string('permit_number')->nullable()->unique();
            $table->timestamp('permit_issued_at')->nullable();

            // priority programs
            $table->foreignId('first_priority_program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->foreignId('second_priority_program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->foreignId('final_program_id')->nullable()->constrained('programs')->nullOnDelete();

            $table->timestamp('finalized_at')->nullable();

            $table->timestamps();

            // indexes for performance
            $table->index('status');
            $table->index('step');
            $table->index('examinee_number');
            $table->index('permit_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
