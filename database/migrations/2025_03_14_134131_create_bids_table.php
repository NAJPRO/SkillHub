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
        // Réponse à une annonce
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Freelance qui postule
            $table->foreignId('request_id')->constrained()->onDelete('cascade'); // Annonce du client
            $table->text('message'); // Message du freelance
            $table->decimal('amount', 10, 2); // Prix proposé par le freelance
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending'); // Statut
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
