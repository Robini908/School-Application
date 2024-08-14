<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTeacherIdFromDormsTableAndAddUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dorms', function (Blueprint $table) {
            $table->dropColumn(['teacher_id','dorm_master_id','occupancy','dorm_master']);

            $table->unsignedInteger('user_id')->nullable();
            $table->string('session')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dorms', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['session','user_id']);
        });
    }
}
