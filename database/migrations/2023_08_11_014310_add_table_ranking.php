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
        Schema::create('ranking', function (Blueprint $table) {
            $table->id();
            $table->string('record_type');
            $table->decimal('record', 12, 4);
            $table->unsignedBigInteger('map_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('rank');
            $table->string('sport_code');
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
        //
        Schema::dropIfExists('ranking');
    }
};
