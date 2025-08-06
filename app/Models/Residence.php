<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Residence extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'price_per_night',
        'location',
        'floor',
        'size',
        'surface',
        'max_guests',
        'rating',
        'availability_status',
        'image',
        'is_featured',
        'is_active',
        // Champs de traduction
        'name_en',
        'description_en',
        'short_description_en',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_per_night' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function images()
    {
        return $this->hasMany(ResidenceImage::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'residence_amenities');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('availability_status', 'available');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Slug methods
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($residence) {
            if (empty($residence->slug)) {
                $residence->slug = $residence->generateUniqueSlug($residence->name);
            }
        });

        static::updating(function ($residence) {
            if ($residence->isDirty('name') && empty($residence->slug)) {
                $residence->slug = $residence->generateUniqueSlug($residence->name);
            }
        });
    }

    public function generateUniqueSlug($name)
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // Accessors
    public function getMaxGuestsAttribute($value)
    {
        return $value ?? 4; // Valeur par défaut si null
    }

    public function getPricePerNightAttribute($value)
    {
        // Si price_per_night est null ou 0, utilise price, sinon utilise price_per_night
        return ($value && $value > 0) ? $value : ($this->attributes['price'] ?? 0);
    }

    public function getSurfaceAttribute($value)
    {
        return $value ?? $this->size; // Utilise size si surface est null
    }

    // Méthode helper pour obtenir le prix d'affichage
    public function getDisplayPriceAttribute()
    {
        $price = $this->price_per_night;
        return $price > 0 ? $price : ($this->attributes['price'] ?? 0);
    }
}
