<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFks extends Migration
{
    public function up()
    {
        // Foreign key constraints for 'lgas' table
        Schema::table('lgas', function (Blueprint $table) {
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
        });

        // Foreign key constraints for 'users' table
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('lga_id')->references('id')->on('lgas')->onDelete('set null');
            $table->foreign('bg_id')->references('id')->on('blood_groups')->onDelete('set null');
            $table->foreign('nal_id')->references('id')->on('nationalities')->onDelete('set null');
        });

        // Foreign key constraints for 'my_classes' table
        Schema::table('my_classes', function (Blueprint $table) {
            $table->foreign('class_type_id')->references('id')->on('class_types')->onDelete('set null');

        });

        // Foreign key constraints for 'sections' table
        Schema::table('sections', function (Blueprint $table) {
            $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');
        });

        // Foreign key constraints for 'student_records' table
        Schema::table('student_records', function (Blueprint $table) {
            // $table->foreign('parent_id', 'fks_user_id_foreign')->references('id')->on('users')->onDelete('set null');
            $table->foreign('my_class_id', 'fks_class_id_foreign')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('section_id', 'fks_section_id_foreign')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('dorm_id', 'fks_dorm_id_foreign')->references('id')->on('dorms')->onDelete('set null');
            $table->foreign('nal_id', 'fks_nationality_id_foreign')->references('id')->on('nationalities')->onDelete('set null');
            $table->foreign('state_id', 'fks_state_id_foreign')->references('id')->on('states')->onDelete('set null');
            $table->foreign('lga_id', 'fks_lga_id_foreign')->references('id')->on('lgas')->onDelete('set null');
            $table->foreign('bg_id', 'fks_blood_group_id_foreign')->references('id')->on('blood_groups')->onDelete('set null');
        });

        // Foreign key constraints for 'marks' table
        Schema::table('marks', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('set null');
        });

        // Foreign key constraints for 'grades' table
        Schema::table('grades', function (Blueprint $table) {
            $table->foreign('class_type_id')->references('id')->on('class_types')->onDelete('cascade');
        });

        // Foreign key constraints for 'pins' table
        Schema::table('pins', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Foreign key constraints for 'exam_records' table
        Schema::table('exam_records', function (Blueprint $table) {
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });

        // Foreign key constraints for 'books' table
        Schema::table('books', function (Blueprint $table) {
            $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
        });

        // Foreign key constraints for 'book_requests' table
        Schema::table('book_requests', function (Blueprint $table) {
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Foreign key constraints for 'staff_records' table
        Schema::table('staff_records', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Foreign key constraints for 'payments' table
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
        });

        // Foreign key constraints for 'payment_records' table
        Schema::table('payment_records', function (Blueprint $table) {
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Foreign key constraints for 'receipts' table
        Schema::table('receipts', function (Blueprint $table) {
            $table->foreign('pr_id')->references('id')->on('payment_records')->onDelete('cascade');
        });

        // Foreign key constraints for 'time_table_records' table
        Schema::table('time_table_records', function (Blueprint $table) {
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
        });

        // Foreign key constraints for 'time_slots' table
        Schema::table('time_slots', function (Blueprint $table) {
            $table->foreign('ttr_id')->references('id')->on('time_table_records')->onDelete('cascade');
        });

        // Foreign key constraints for 'time_tables' table
        Schema::table('time_tables', function (Blueprint $table) {
            $table->foreign('ttr_id')->references('id')->on('time_table_records')->onDelete('cascade');
            $table->foreign('ts_id')->references('id')->on('time_slots')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down()
    {
        // Drop foreign keys in reverse order
        Schema::table('time_tables', function (Blueprint $table) {
            $table->dropForeign(['ttr_id']);
            $table->dropForeign(['ts_id']);
            $table->dropForeign(['subject_id']);
        });

        Schema::table('time_slots', function (Blueprint $table) {
            $table->dropForeign(['ttr_id']);
        });

        Schema::table('time_table_records', function (Blueprint $table) {
            $table->dropForeign(['exam_id']);
            $table->dropForeign(['my_class_id']);
        });

        Schema::table('receipts', function (Blueprint $table) {
            $table->dropForeign(['pr_id']);
        });

        Schema::table('payment_records', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
            $table->dropForeign(['student_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['my_class_id']);
        });

        Schema::table('staff_records', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('book_requests', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['my_class_id']);
        });

        Schema::table('exam_records', function (Blueprint $table) {
            $table->dropForeign(['exam_id']);
            $table->dropForeign(['my_class_id']);
            $table->dropForeign(['student_id']);
            $table->dropForeign(['section_id']);
        });

        Schema::table('pins', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['student_id']);
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['class_type_id']);
        });

        Schema::table('marks', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['my_class_id']);
            $table->dropForeign(['section_id']);
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['exam_id']);
            $table->dropForeign(['grade_id']);
        });

        Schema::table('student_records', function (Blueprint $table) {
            $table->dropForeign(['my_class_id']);
            $table->dropForeign(['section_id']);
            $table->dropForeign(['dorm_id']);
            $table->dropForeign(['nal_id']);
            $table->dropForeign(['state_id']);
            $table->dropForeign(['lga_id']);
            $table->dropForeign(['bg_id']);
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['my_class_id']);
            $table->dropForeign(['teacher_id']);
        });

        Schema::table('my_classes', function (Blueprint $table) {
            $table->dropForeign(['class_type_id']);
           
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropForeign(['lga_id']);
            $table->dropForeign(['bg_id']);
            $table->dropForeign(['nal_id']);
        });

        Schema::table('lgas', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
        });
    }
}
