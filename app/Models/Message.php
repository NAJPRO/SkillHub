<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id', 'content', 'status'];


    // Définir des constantes pour les valeurs de l'enum
    const STATUS_SEND = 'sent';
    const STATUS_READ= 'read';
    const STATUS_ARCHIVED = 'archived';

    /**
     * Relation : l'utilisateur qui envoie le message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relation : l'utilisateur qui reçoit le message
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Vérifie si le message est lu
     */
    public function isRead()
    {
        return $this->status === self::STATUS_READ;
    }
}
