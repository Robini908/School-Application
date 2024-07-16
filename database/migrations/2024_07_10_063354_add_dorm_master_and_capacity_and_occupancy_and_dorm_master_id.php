<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDormMasterAndCapacityAndOccupancyAndDormMasterId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dorms', function (Blueprint $table) {
            $table->unsignedInteger('dorm_master_id'); 
            $table->string('dorm_master');
            $table->unsignedInteger('capacity');
            $table->unsignedInteger('occupancy');
           
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
            $table->dropColumn([ 'dorm_master', 'capacity', 'occupancy']);
        });
    }
}
