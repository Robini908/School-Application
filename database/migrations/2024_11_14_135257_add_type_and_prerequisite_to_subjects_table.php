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
        Schema::table('subjects', function (Blueprint $table) {
            // Add the 'type' column to define whether the subject is compulsory or elective
            $table->enum('type', ['compulsory', 'elective'])->default('elective')->after('abbreviation');
            
            // Add the 'prerequisite_id' column to reference other subjects in the same table (self-referencing)
            $table->unsignedInteger('prerequisite_id')->nullable()->after('category_id');
            
            // Add a foreign key constraint to 'prerequisite_id' referencing the 'id' of the same table (self-referencing)
            $table->foreign('prerequisite_id')->references('id')->on('subjects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Drop the 'type' column
            $table->dropColumn('type');
            
            // Drop the 'prerequisite_id' column and its foreign key
            $table->dropForeign(['prerequisite_id']);
            $table->dropColumn('prerequisite_id');
        });
    }
};
