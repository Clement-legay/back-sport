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
        Schema::create('muscle_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('muscle_id');
            $table->integer('order')->nullable();
            $table->enum('media_type', ['image', 'video']);
            $table->string('media_path');
            $table->foreign('muscle_id')->references('id')->on('muscles')->onDelete('cascade');
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->foreign('creator_id')->references('id')->on('users');
            $table->unsignedBigInteger('updater_id')->nullable();
            $table->foreign('updater_id')->references('id')->on('users');
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

        Schema::dropIfExists('muscle_media');
    }
};
