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
        // Annonce de service posté par les utilisateurs recherchant un freelance pour leur travail
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Client qui poste l'annonce
            $table->string('title'); // Titre de l'annonce
            $table->text('description'); // Description détaillée
            $table->decimal('budget', 10, 2)->nullable(); // Budget prévu
            $table->enum('status', ['open', 'in_progress', 'closed', 'completed'])->default('open'); // Statut

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
