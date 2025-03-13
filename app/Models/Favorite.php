<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'service_id'];

    /**
     * Relation : l'utilisateur qui a ajouté le service aux favoris
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : le service ajouté aux favoris
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

