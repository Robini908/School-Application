<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldsInGradingSystemsTable extends Migration
{
    public function up()
    {
        Schema::table('grading_systems', function (Blueprint $table) {
            // Change 'description' to accept long text
            $table->text('description')->nullable()->change(); // Long description of the grading system
            
            // Change 'rules' to accept long text
            $table->text('rules')->nullable()->change(); // Long rules to be applied to the grading system
        });
    }

    public function down()
    {
        Schema::table('grading_systems', function (Blueprint $table) {
            // Revert back to string if you need to roll back the migration
            $table->string('description')->nullable()->change(); // Brief description of the grading system
            $table->string('rules')->nullable()->change(); // Rules to be applied to the grading system
        });
    }
}
