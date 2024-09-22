<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToGradingSystemsTable extends Migration
{
    public function up()
    {
        Schema::table('grading_systems', function (Blueprint $table) {
            $table->string('description')->nullable(); // Brief description of the grading system
            $table->date('effective_date')->nullable(); // Effective date for the grading system
            $table->string('rules')->nullable(); // Rules to be applied on the grading system
        });
    }

    public function down()
    {
        Schema::table('grading_systems', function (Blueprint $table) {
            $table->dropColumn(['description', 'weighting', 'effective_date', 'rules']);
        });
    }
}
