<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parent_details', function (Blueprint $table) {
            $table->string('parent_id_no')->primary();
            $table->string('parent_first_name');
            $table->string('parent_middle_name')->nullable();
            $table->string('parent_last_name');            
            $table->string('parent_phone_number');
            $table->string('parent_email')->unique();
            $table->string('parent_password');
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
        Schema::dropIfExists('parent_details');
    }
}
