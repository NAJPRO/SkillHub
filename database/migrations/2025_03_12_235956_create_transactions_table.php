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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade'); // Utilisateur ayant effectué la transaction
            $table->foreignIdFor(\App\Models\Order::class)->constrained()->onDelete('cascade'); // Commande liée à la transaction
            $table->decimal('amount', 10, 2); // Montant de la transaction
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending'); // Statut de la transaction
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
