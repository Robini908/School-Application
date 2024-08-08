<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable(); //will hold the parent id
            $table->unsignedInteger('my_class_id');
            $table->unsignedInteger('section_id');
            $table->string('adm_no', 30)->unique()->nullable();           
            $table->unsignedInteger('dorm_id')->nullable();
            $table->string('year_admitted')->nullable();
            $table->string('kcpe');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('gender');
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->unsignedInteger('nal_id')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->string('town')->nullable();
            $table->unsignedInteger('bg_id')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->default('unverified');
            $table->string('student_password')->nullable();
            $table->timestamps();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
}
