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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade'); // Acheteur
            $table->foreignIdFor(\App\Models\ServicePricing::class)->constrained()->onDelete('cascade'); // Service acheté
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending'); // Statut de la commande
            $table->decimal('total_price', 10, 2); // Prix total
            $table->timestamp('delivery_date')->nullable(); // Date prévue de livraison
            $table->text('notes')->nullable(); // Notes du client
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
