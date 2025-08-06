<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'residence_id',
        'booking_id',
        'rating',
        'title',
        'comment',
        'is_approved',
        'helpful_votes',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'rating' => 'integer',
        'helpful_votes' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function residence()
    {
        return $this->belongsTo(Residence::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
