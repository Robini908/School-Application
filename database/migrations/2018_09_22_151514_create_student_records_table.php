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
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('my_class_id');
            $table->unsignedInteger('section_id');
            $table->string('adm_no', 30)->unique()->nullable();
            $table->unsignedInteger('my_parent_id')->nullable();
            $table->unsignedInteger('dorm_id')->nullable();
            $table->string('dorm_room_no')->nullable();
            $table->string('session');
            $table->string('house')->nullable();
            $table->tinyInteger('age')->nullable();
            $table->string('year_admitted')->nullable();
            $table->tinyInteger('grad')->default(0);
            $table->string('grad_date')->nullable();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('gender');
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->unsignedInteger('nal_id')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('lga_id')->nullable();
            $table->unsignedInteger('bg_id')->nullable();
            $table->string('photo')->nullable();
            $table->string('parent_first_name');
            $table->string('parent_middle_name');
            $table->string('parent_last_name');
            $table->string('nin');
            $table->string('parent_phone');
            $table->string('parent_email');
            $table->string('password'); // This field will be hashed in Laravel authentication setup

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('my_class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('my_parent_id')->references('id')->on('parents')->onDelete('set null');
            $table->foreign('dorm_id')->references('id')->on('dormitories')->onDelete('set null');
            $table->foreign('nal_id')->references('id')->on('nationals')->onDelete('set null');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('lga_id')->references('id')->on('lgas')->onDelete('set null');
            $table->foreign('bg_id')->references('id')->on('blood_groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
}
