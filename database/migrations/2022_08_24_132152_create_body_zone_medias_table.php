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
        Schema::create('body_zone_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('body_zone_id');
            $table->integer('order')->nullable();
            $table->foreign('body_zone_id')->references('id')->on('body_zones')->onDelete('cascade');
            $table->string('media_path');
            $table->enum('media_type', ['image', 'video']);
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
        Schema::dropIfExists('body_zone_media');
    }
};
