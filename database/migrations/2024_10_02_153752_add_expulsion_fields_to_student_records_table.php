<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpulsionFieldsToStudentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_records', function (Blueprint $table) {
            // Add expulsion fields
            $table->boolean('is_expelled')->default(false); // To track if the student is expelled
            $table->text('expulsion_reason')->nullable(); // To store the reason for expulsion
            $table->unsignedInteger('expelled_by')->nullable(); // To track which admin expelled the student
            $table->timestamp('expulsion_date')->nullable(); // To record when the expulsion took place
            $table->enum('expulsion_type', ['dismissal', 'withdrawal', 'permanent_exclusion'])->nullable(); // Type of expulsion
            $table->timestamp('expulsion_end_date')->nullable(); // Expulsion end date for temporary expulsions
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
            // Drop expulsion fields
            $table->dropColumn('is_expelled');
            $table->dropColumn('expulsion_reason');
            $table->dropColumn('expelled_by');
            $table->dropColumn('expulsion_date');
            $table->dropColumn('expulsion_type');
            $table->dropColumn('expulsion_end_date'); 
        });
    }
}

