<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'service_id', 'rating', 'comment'];

    /**
     * Relation : l'utilisateur qui a laissé l'avis
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : le service évalué
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Vérifie si l'avis est une évaluation positive
     */
    public function isPositive()
    {
        return $this->rating >= 4; // Note de 4 ou plus est considérée comme positive
    }
}
