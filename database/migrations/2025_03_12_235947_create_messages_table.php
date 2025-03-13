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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class, 'sender_id')->constrained()->onDelete('cascade'); // L'utilisateur qui envoie le message
            $table->foreignIdFor(\App\Models\User::class, 'receiver_id')->constrained()->onDelete('cascade'); // L'utilisateur qui reçoit le message
            $table->text('content'); // Contenu du message
            $table->enum('status', ['sent', 'read', 'archived'])->default('sent'); // Statut du message
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
