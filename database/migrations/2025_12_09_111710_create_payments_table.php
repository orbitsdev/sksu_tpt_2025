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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // exam & applicant relations
            $table->foreignId('examination_id')->constrained()->onDelete('cascade');
            $table->foreignId('applicant_id')->constrained('users')->onDelete('cascade');

            // cashier (staff user)
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();

            // campus where payment was processed
            $table->foreignId('campus_id')->nullable()->constrained()->nullOnDelete();

            // financial details
            $table->decimal('amount', 10, 2)->comment('Expected payment amount');
            $table->decimal('amount_paid', 10, 2)->nullable()->comment('Amount actually paid');
            $table->decimal('change', 10, 2)->default(0)->comment('Auto-computed change');

            // payment configuration
            $table->string('payment_method')->comment('CASH, GCASH, BANK_TRANSFER, PAYMAYA, CARD, ETC.');
            $table->string('payment_channel')->nullable()->comment('GCash, BDO, BPI, LandBank, etc.');
            $table->string('payment_reference')->nullable()->comment('Reference number for digital payments');

            // receipt
            $table->string('official_receipt_number')->nullable()->unique();
            $table->string('receipt_file')->nullable();

            // status workflow
            $table->string('status')->default('PENDING')
                ->comment('PENDING, VERIFIED, REJECTED, REFUNDED');

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('verified_at')->nullable();

            // verified by staff
            $table->foreignId('verified_by')->nullable()
                ->constrained('users')->nullOnDelete();

            $table->timestamps();

            // indexes
            $table->index('status');
            $table->index('payment_method');
            $table->index('payment_channel');
            $table->index('examination_id');
            $table->index('applicant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
