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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gander', ['man', 'woman']);
            $table->string('phone');
            $table->text('address');
            $table->date('date_join');
            $table->text('picture');
            $table->foreignId('role_id')
                ->nullable()
                ->constrained('roles')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('employees', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['user_id']);
        });
    }
};
