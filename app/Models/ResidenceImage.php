<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidenceImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'residence_id',
        'image_path',
        'alt_text',
        'is_primary',
        'order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // Relationships
    public function residence()
    {
        return $this->belongsTo(Residence::class);
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
