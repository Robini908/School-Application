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
    Schema::create('notifications', function (Blueprint $table) {
        $table->id();
        $table->string('type'); // Notification type (e.g., 'system', 'broadcast', 'database')
        $table->morphs('notifiable'); // Polymorphic relationship for users or other models
        $table->text('data'); // JSON data for the notification
        $table->timestamp('read_at')->nullable(); // Timestamp when the notification was read
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
