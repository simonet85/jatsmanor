<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'residence_id',
        'room_id',
        'check_in',
        'check_out', 
        'guests',
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'total_nights',
        'price_per_night',
        'total_amount',
        'cleaning_fee',
        'security_deposit',
        'tax_amount',
        'status',
        'special_requests',
        'confirmation_code',
        'booking_reference',
        'payment_status',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_amount' => 'decimal:2',
        'cleaning_fee' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'deleted_at' => 'datetime',
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

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors & Mutators
    public function getNightsAttribute()
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    public function getGrandTotalAttribute()
    {
        return $this->total_amount + $this->cleaning_fee + $this->security_deposit + $this->tax_amount;
    }
}
