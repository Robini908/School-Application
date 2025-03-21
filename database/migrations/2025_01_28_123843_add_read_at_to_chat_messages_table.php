<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->string('type')->default('text'); // Message type (text, image, file, etc.)
            $table->string('attachment')->nullable(); // Attachment URL or path
            $table->timestamp('read_at')->nullable();
            $table->timestamp('delivered_at')->nullable(); // Timestamp for when the message was delivered
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn(['type', 'attachment', 'read_at', 'delivered_at']);
        });
    }
};
