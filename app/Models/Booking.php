<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'service_id',
        'client_id',
        'provider_id',
        'booking_date',
        'duration',
        'total_amount',
        'notes',
        'status',
        'cancellation_reason',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'is_reported',
        'report_reason',
        'report_description',
        'reported_at',
        'reported_by',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'is_reported' => 'boolean',
        'reported_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            $booking->booking_number = 'BK' . date('Y') . str_pad(Booking::count() + 1, 6, '0', STR_PAD_LEFT);
        });
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function canBeReviewed()
    {
        return $this->isCompleted() && !$this->review;
    }
}
