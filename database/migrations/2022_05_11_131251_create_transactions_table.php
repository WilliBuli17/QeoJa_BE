<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('subtotal_price');
            $table->integer('shipping_cost');
            $table->integer('tax');
            $table->integer('grand_total_price');
            $table->text('receipt_of_payment');
            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('customers')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('address_id')
                ->nullable()
                ->constrained('addresses')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('bank_payment_id')
                ->nullable()
                ->constrained('bank_payments')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('transaction_status_id')
                ->nullable()
                ->constrained('transaction_statuses')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['address_id']);
            $table->dropForeign(['bank_payment_id']);
            $table->dropForeign(['transaction_status_id']);
        });
    }
};