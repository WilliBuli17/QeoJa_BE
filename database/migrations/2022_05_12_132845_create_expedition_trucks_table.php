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
        Schema::create('expedition_trucks', function (Blueprint $table) {
            $table->id();
            $table->string('license_id')->unique();
            $table->double('min_volume');
            $table->double('max_volume');
            $table->text('picture');
            $table->enum('status', ['available', 'not available']);
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
        Schema::dropIfExists('expedition_trucks');
    }
};
