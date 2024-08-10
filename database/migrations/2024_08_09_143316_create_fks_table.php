<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFks extends Migration
{
    public function up()
    {
        Schema::table('lgas', function (Blueprint $table) {
            $table->foreign('state_id', 'fk_lgas_state_id')->references('id')->on('states')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('state_id', 'fk_users_state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('lga_id', 'fk_users_lga_id')->references('id')->on('lgas')->onDelete('set null');
            $table->foreign('bg_id', 'fk_users_bg_id')->references('id')->on('blood_groups')->onDelete('set null');
            $table->foreign('nal_id', 'fk_users_nal_id')->references('id')->on('nationalities')->onDelete('set null');
        });

        Schema::table('my_classes', function (Blueprint $table) {
            $table->foreign('class_type_id', 'fk_my_classes_class_type_id')->references('id')->on('class_types')->onDelete('set null');
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->foreign('my_class_id', 'fk_sections_my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('teacher_id', 'fk_sections_teacher_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('student_records', function (Blueprint $table) {
            $table->foreign('my_class_id', 'fk_student_records_my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('section_id', 'fk_student_records_section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('dorm_id', 'fk_student_records_dorm_id')->references('id')->on('dorms')->onDelete('set null');
            $table->foreign('nal_id', 'fk_student_records_nal_id')->references('id')->on('nationalities')->onDelete('set null');
            $table->foreign('state_id', 'fk_student_records_state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('lga_id', 'fk_student_records_lga_id')->references('id')->on('lgas')->onDelete('set null');
            $table->foreign('bg_id', 'fk_student_records_bg_id')->references('id')->on('blood_groups')->onDelete('set null');
        });

        Schema::table('marks', function (Blueprint $table) {
            $table->foreign('student_id', 'fk_marks_student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('my_class_id', 'fk_marks_my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('section_id', 'fk_marks_section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('subject_id', 'fk_marks_subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('exam_id', 'fk_marks_exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('grade_id', 'fk_marks_grade_id')->references('id')->on('grades')->onDelete('set null');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->foreign('class_type_id', 'fk_grades_class_type_id')->references('id')->on('class_types')->onDelete('cascade');
        });

        Schema::table('pins', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_pins_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('student_id', 'fk_pins_student_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('exam_records', function (Blueprint $table) {
            $table->foreign('exam_id', 'fk_exam_records_exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('my_class_id', 'fk_exam_records_my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('student_id', 'fk_exam_records_student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('section_id', 'fk_exam_records_section_id')->references('id')->on('sections')->onDelete('cascade');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->foreign('my_class_id', 'fk_books_my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
        });

        Schema::table('book_requests', function (Blueprint $table) {
            $table->foreign('book_id', 'fk_book_requests_book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('user_id', 'fk_book_requests_user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('staff_records', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_staff_records_user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('my_class_id', 'fk_payments_my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
        });

        Schema::table('payment_records', function (Blueprint $table) {
            $table->foreign('payment_id', 'fk_payment_records_payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('student_id', 'fk_payment_records_student_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('receipts', function (Blueprint $table) {
            $table->foreign('pr_id', 'fk_receipts_pr_id')->references('id')->on('payment_records')->onDelete('cascade');
        });

        Schema::table('time_table_records', function (Blueprint $table) {
            $table->foreign('exam_id', 'fk_time_table_records_exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('my_class_id', 'fk_time_table_records_my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
        });

        Schema::table('time_slots', function (Blueprint $table) {
            $table->foreign('ttr_id', 'fk_time_slots_ttr_id')->references('id')->on('time_table_records')->onDelete('cascade');
        });

        Schema::table('time_tables', function (Blueprint $table) {
            $table->foreign('ttr_id', 'fk_time_tables_ttr_id')->references('id')->on('time_table_records')->onDelete('cascade');
            $table->foreign('ts_id', 'fk_time_tables_ts_id')->references('id')->on('time_slots')->onDelete('cascade');
            $table->foreign('subject_id', 'fk_time_tables_subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('time_tables', function (Blueprint $table) {
            $table->dropForeign(['ttr_id', 'ts_id', 'subject_id']);
        });

        Schema::table('time_slots', function (Blueprint $table) {
            $table->dropForeign(['ttr_id']);
        });

        Schema::table('time_table_records', function (Blueprint $table) {
            $table->dropForeign(['exam_id', 'my_class_id']);
        });

        Schema::table('receipts', function (Blueprint $table) {
            $table->dropForeign(['pr_id']);
        });

        Schema::table('payment_records', function (Blueprint $table) {
            $table->dropForeign(['payment_id', 'student_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['my_class_id']);
        });

        Schema::table('staff_records', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('book_requests', function (Blueprint $table) {
            $table->dropForeign(['book_id', 'user_id']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['my_class_id']);
        });

        Schema::table('exam_records', function (Blueprint $table) {
            $table->dropForeign(['exam_id', 'my_class_id', 'student_id', 'section_id']);
        });

        Schema::table('pins', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'student_id']);
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['class_type_id']);
        });

        Schema::table('marks', function (Blueprint $table) {
            $table->dropForeign(['student_id', 'my_class_id', 'section_id', 'subject_id', 'exam_id', 'grade_id']);
        });

        Schema::table('student_records', function (Blueprint $table) {
            $table->dropForeign(['my_class_id', 'section_id', 'dorm_id', 'nal_id', 'state_id', 'lga_id', 'bg_id']);
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['my_class_id', 'teacher_id']);
        });

        Schema::table('my_classes', function (Blueprint $table) {
            $table->dropForeign(['class_type_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['state_id', 'lga_id', 'bg_id', 'nal_id']);
        });

        Schema::table('lgas', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
        });
    }
}
