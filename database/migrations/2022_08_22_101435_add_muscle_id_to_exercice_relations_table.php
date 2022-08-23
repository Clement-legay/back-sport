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
        Schema::table('exercice_relations', function (Blueprint $table) {
            $table->unsignedBigInteger('muscle_id');
            $table->foreign('muscle_id')->references('id')->on('muscles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exercice_relations', function (Blueprint $table) {
            $table->dropForeign(['muscle_id']);
            $table->dropColumn('muscle_id');
        });
    }
};
