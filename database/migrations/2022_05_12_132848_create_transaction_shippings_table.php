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
        Schema::create('transaction_shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')
                ->nullable()
                ->constrained('transactions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('employees')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('expedition_truck_id')
                ->nullable()
                ->constrained('expedition_trucks')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->date('delivery_date');
            $table->date('arrived_date')->nullable();
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
        Schema::dropIfExists('transaction_shippings', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['expedition_truck_id']);
        });
    }
};
