<?php

use App\Models\GradingSystem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradingRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grading_ranges', function (Blueprint $table) {
            $table->id();
            $table->integer('range_from');
            $table->integer('range_to');
            $table->string('grade');
            $table->foreignIdFor(GradingSystem::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grading_ranges');
    }
}
