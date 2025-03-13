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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade'); // L'utilisateur qui laisse un avis
            $table->foreignIdFor(\App\Models\Service::class)->constrained()->onDelete('cascade'); // Service évalué
            $table->integer('rating')->unsigned()->min(1)->max(5); // Note entre 1 et 5
            $table->text('comment')->nullable(); // Commentaire de l'utilisateur
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
