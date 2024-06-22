<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToStudentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_records', function (Blueprint $table) {
            $table->string('first_name')->after('id');
            $table->string('middle_name')->after('first_name');
            $table->string('last_name')->after('middle_name');
            $table->string('email')->nullable()->after('last_name');
            $table->string('gender')->after('email');
            $table->string('phone')->nullable()->after('gender');
            $table->date('dob')->nullable()->after('phone');
            $table->unsignedInteger('nal_id')->nullable()->after('dob');
            $table->unsignedInteger('state_id')->nullable()->after('nal_id');
            $table->unsignedInteger('lga_id')->nullable()->after('state_id');
            $table->unsignedInteger('bg_id')->nullable()->after('lga_id');
            $table->string('photo')->nullable()->after('bg_id');
            $table->string('parent_first_name')->after('photo');
            $table->string('parent_middle_name')->after('parent_first_name');
            $table->string('parent_last_name')->after('parent_middle_name');
            $table->string('nin')->after('parent_last_name');
            $table->string('parent_phone')->after('nin');
            $table->string('parent_email')->after('parent_phone');
            $table->string('password')->after('parent_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_records', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'email',
                'gender',
                'phone',
                'dob',
                'nal_id',
                'state_id',
                'lga_id',
                'bg_id',
                'photo',
                'parent_first_name',
                'parent_middle_name',
                'parent_last_name',
                'nin',
                'parent_phone',
                'parent_email',
                'password'
            ]);
        });
    }
}
