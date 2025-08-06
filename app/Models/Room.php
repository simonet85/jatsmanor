<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'residence_id',
        'name',
        'type',
        'description',
        'max_occupancy',
        'price_modifier',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
    ];

    // Relationships
    public function residence()
    {
        return $this->belongsTo(Residence::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
