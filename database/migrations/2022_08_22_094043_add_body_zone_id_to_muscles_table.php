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
        Schema::table('muscles', function (Blueprint $table) {
            $table->unsignedBigInteger('body_zone_id');
            $table->foreign('body_zone_id')->references('id')->on('body_zones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('muscles', function (Blueprint $table) {
            $table->dropForeign(['body_zone_id']);
            $table->dropColumn('body_zone_id');
        });
    }
};
