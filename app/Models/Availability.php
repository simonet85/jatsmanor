<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'residence_id',
        'date',
        'is_available',
        'price_override',
        'min_stay_override',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'is_available' => 'boolean',
        'price_override' => 'decimal:2',
    ];

    // Relationships
    public function residence()
    {
        return $this->belongsTo(Residence::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeUnavailable($query)
    {
        return $query->where('is_available', false);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
