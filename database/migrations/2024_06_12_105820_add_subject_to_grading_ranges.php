<?php

use App\Models\Subject;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubjectToGradingRanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grading_ranges', function (Blueprint $table) {
            $table->unsignedInteger('subject_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grading_ranges', function (Blueprint $table) {
            //
            $table->dropColumn('subject_id');
        });
    }
}
