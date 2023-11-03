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
        Schema::create('sports_record', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->decimal('record', 8, 4);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('map_id');
            $table->timestamps();
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('map_id')->references('id')->on('map_list')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sports_record', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropConstrainedForeignId('map_id');
        });
        Schema::dropIfExists('sports_record');
    }
};
