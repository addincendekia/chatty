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
        Schema::create('room_message_receivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_message_id');
            $table->unsignedBigInteger('room_user_id');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('room_message_id')->references('id')->on('room_messages');
            $table->foreign('room_user_id')->references('id')->on('room_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_message_receivers');
    }
};
