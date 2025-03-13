<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'service_pricing_id', 'status', 'total_price', 'delivery_date', 'notes'];

    // Définir des constantes pour les valeurs de l'enum
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_PROGRESS = 'in_progress';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Relation : l'acheteur (Utilisateur)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : le service commandé
     */
    public function servicePricing()
    {
        return $this->belongsTo(ServicePricing::class);
    }

    /**
     * Vérifie si la commande est terminée
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}

