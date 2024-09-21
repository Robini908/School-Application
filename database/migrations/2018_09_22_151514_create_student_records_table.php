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
        // Creating the parent_details table
        Schema::create('parent_details', function (Blueprint $table) {
            $table->string('parent_id_no')->primary();  // Unique parent ID (primary key)
            $table->string('parent_first_name');
            $table->string('parent_middle_name')->nullable();
            $table->string('parent_last_name');
            $table->string('parent_phone_number');
            $table->string('parent_email')->unique();
            $table->string('parent_password');
            $table->timestamps();
        });

        // Creating the student_records table
        Schema::create('student_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('parent_id_no')->nullable(); // Foreign key column for parent
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
            $table->unsignedInteger('lga_id')->nullable();
            $table->string('town')->nullable();
            $table->unsignedInteger('bg_id')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->default('unverified');
            $table->string('student_password')->nullable();
            $table->timestamps();            

            // Foreign key constraint for parent_id_no
            $table->foreign('parent_id_no')
                  ->references('parent_id_no')
                  ->on('parent_details')
                  ->onDelete('set null');  // If the parent is deleted, set parent_id_no to null

            // Add any other foreign keys if needed for my_class_id, section_id, etc.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Dropping the student_records table
        Schema::dropIfExists('student_records');

        // Dropping the parent_details table
        Schema::dropIfExists('parent_details');
    }
}
