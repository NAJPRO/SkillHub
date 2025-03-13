<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_id', 'amount', 'status'];

    // Définir des constantes pour les valeurs de l'enum
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    /**
     * Relation : l'utilisateur qui a effectué la transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : la commande associée à la transaction
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Vérifie si la transaction est réussie
     */
    public function isSuccessful()
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}

