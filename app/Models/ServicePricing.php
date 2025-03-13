<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServicePricing extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'type', 'price', 'description'];

    // Définir des constantes pour les valeurs de l'enum
    const STATUS_BASIC = 'basic';
    const STATUS_STANDARD = 'standard';
    const STATUS_PREMIUM = 'premium';
    /**
     * Relation avec le service
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
