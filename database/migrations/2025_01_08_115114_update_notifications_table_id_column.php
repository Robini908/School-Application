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
    Schema::table('notifications', function (Blueprint $table) {
        $table->uuid('id')->change(); // Change the column type to UUID
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
