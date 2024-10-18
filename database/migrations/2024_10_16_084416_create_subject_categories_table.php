<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('subject_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Unique category name
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subject_categories');
    }
}
