<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category_id', 'title', 'slug', 'description', 'status'];

    // Définir des constantes pour les valeurs de l'enum
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            // Génère un slug à partir du titre
            $service->slug = Str::slug($service->title);
        });

        static::updating(function ($service) {
            // Regénère le slug si le titre change
            $service->slug = Str::slug($service->title);
        });
    }


      /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug'; // Indique que la route doit utiliser le champ "slug"
    }
    
    /**
     * Relation avec l'utilisateur (freelancer)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la catégorie
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relation avec les prix (Basic, Standard, Premium)
     */
    public function pricings()
    {
        return $this->hasMany(ServicePricing::class);
    }

    /**
     * Relation avec les avis (reviews)
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relation avec les favoris
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
