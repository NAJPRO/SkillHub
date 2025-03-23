<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'budget', 'status'
    ];

    // Relation avec l'utilisateur (Client)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec les propositions des freelances 
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }
}
