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
        Schema::table('sports_record', function(Blueprint $table) {
            $table->decimal('record', 12, 4)->change();
        });
        Schema::table('users', function(Blueprint $table) {
            $table->unsignedBigInteger('group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sports_record', function(Blueprint $table) {
            $table->decimal('record', 8, 4)->change();
        });
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }
};
