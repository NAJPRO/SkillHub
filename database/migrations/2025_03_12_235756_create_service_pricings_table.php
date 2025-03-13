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
        Schema::create('service_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Service::class)->constrained()->onDelete('cascade'); // Lié à un service
            $table->enum('type', ['basic', 'standard', 'premium']); // Type de tarification
            $table->decimal('price', 10, 2); // Prix du service
            $table->text('description'); // Description de ce plan de tarification
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_pricings');
    }
};
