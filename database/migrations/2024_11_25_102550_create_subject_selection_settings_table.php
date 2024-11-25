<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('subject_selection_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('class_id'); // References the class (MyClass table)
            $table->boolean('is_subject_selection_enabled')->default(false);
            $table->timestamp('deadline')->nullable();  // Add the deadline column
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('class_id')->references('id')->on('my_classes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subject_selection_settings');
    }
};
