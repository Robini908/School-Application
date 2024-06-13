<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUniqueIndexFromExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropUnique(['term', 'year']);
        });
        // add unique index with name included and the two above columns
        Schema::table('exams', function (Blueprint $table) {
            $table->unique(['term', 'year', 'name'], 'exams_term_year_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->unique(['term', 'year']);
        });
        Schema::table('exams', function (Blueprint $table) {
            $table->dropUnique(['term', 'year', 'name']);
        });
    }
}
