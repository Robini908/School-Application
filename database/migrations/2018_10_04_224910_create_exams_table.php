<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('term');
            $table->string('year', 40);
            $table->unsignedInteger('class_id'); // Foreign key for class
            $table->unsignedInteger('section_id')->nullable(); // Foreign key for section
            $table->timestamps();
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->unique(['term', 'year']);
            $table->foreign('class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null'); // Link to sections, set to null on delete
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
            $table->dropForeign(['class_id']);
            $table->dropForeign(['section_id']); // Drop section foreign key
        });
        
        Schema::dropIfExists('exams');
    }
}
