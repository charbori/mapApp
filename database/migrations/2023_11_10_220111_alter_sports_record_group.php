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
        Schema::table('sports_record', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->default(1);
            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('sports_record', function (Blueprint $table) {
            $table->dropConstrainedForeignId('group_id');
            $table->dropColumn('group_id');
        });
    }
};
