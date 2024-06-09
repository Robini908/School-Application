<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToGradingRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grading_ranges', function (Blueprint $table) {
            $table->string('remark')->nullable();
            $table->string('gpa')->nullable();
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
            $table->dropColumn(['remark', 'gpa']);
        });
    }
}
