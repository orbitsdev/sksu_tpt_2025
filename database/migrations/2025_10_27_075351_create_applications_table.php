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
            $table->foreignId('examination_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                        $table->integer('step')->default(1);
            $table->bigInteger('exam_number')->nullable();
            $table->text('examinee_number')->nullable();
            $table->text('permit_number')->nullable();
            $table->timestamp('permit_issued_at')->nullable();
            $table->string('status')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index('status');
            $table->index('exam_number');
            $table->index(['examination_id', 'status']); // Composite index for common queries
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
