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
        //
        Schema::table('map_list', function (Blueprint $table) {
            $table->integer('rank');
            $table->integer('player_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('map_list', function (Blueprint $table) {
            $table->dropColumn('rank');
            $table->dropColumn('player_count');
        });
    }
};
