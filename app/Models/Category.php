<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * Relation : une catégorie peut avoir plusieurs services
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Génération automatique du slug lors de la création
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            // Génère un slug à partir du nom
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            // Regénère le slug si le nom change
            $category->slug = Str::slug($category->name);
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
}

