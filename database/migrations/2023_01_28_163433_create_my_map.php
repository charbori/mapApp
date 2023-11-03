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
        Schema::create('map_list', function (Blueprint $table) {
            $table->id();
	    $table->string('title');
	    $table->string('type');
	    $table->text('desc');
	    $table->string('attachment');
	    $table->decimal('latitude', 13, 10);
	    $table->decimal('longitude', 13, 10);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	Schema::table('map_list', function (Blueprint $table) {
	    $table->dropConstrainedForeignId('user_id');
	});
        Schema::dropIfExists('map_list');
    }
};
