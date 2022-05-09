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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->text('address');
            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('customers')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('city_id')
                ->nullable()
                ->constrained('cities')
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
        Schema::dropIfExists('addresses', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['city_id']);
        });
    }
};
