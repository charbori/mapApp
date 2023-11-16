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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner');
            $table->string('name');
            $table->timestamps();
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->foreign('owner')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('users_group', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->timestamps();
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->primary(['group_id', 'user_id']);
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('groups', function (Blueprint $table) {
            $table->dropConstrainedForeignId('owner');
        });
        Schema::table('users_group', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropConstrainedForeignId('group_id');
        });
        Schema::dropIfExists('groups');
        Schema::dropIfExists('users_group');
    }
};
