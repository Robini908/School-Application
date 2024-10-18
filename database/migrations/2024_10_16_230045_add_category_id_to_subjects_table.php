<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToSubjectsTable extends Migration
{
    public function up()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained('subject_categories')->onDelete('cascade'); // Add foreign key constraint
        });
    }

    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['category_id']); // Drop foreign key constraint
            $table->dropColumn('category_id'); // Drop category_id column
        });
    }
}
