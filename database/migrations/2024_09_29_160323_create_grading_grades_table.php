<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradingGradesTable extends Migration
{
    public function up()
{
    Schema::create('grading_grades', function (Blueprint $table) {
        $table->id();
        $table->foreignId('grading_system_id')->constrained()->onDelete('cascade');
        $table->string('grade', 2)->nullable(); // Updated grade field for single character and symbols
        $table->string('remark')->nullable();
        $table->decimal('gpa', 4, 2)->nullable(); // GPA field to allow values up to 13.00
        $table->string('description')->nullable();
        $table->string('additional_info')->nullable();
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('grading_grades');
    }
}
