<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'request_id', 'message', 'amount', 'status'
    ];

    // Relation avec l'utilisateur (Freelance)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec la demande de service (Request)
    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
