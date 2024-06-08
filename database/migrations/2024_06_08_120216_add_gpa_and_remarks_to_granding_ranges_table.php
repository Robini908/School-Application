<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGpaAndRemarksToGrandingRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grading_ranges', function (Blueprint $table) {
            //
            $table->string('remark')->nullable(); // Add remark column
            $table->string('gpa')->nullable(); // Add gpa column
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
            $table->dropColumn(['remark', 'gpa']);
        });
    }
}
