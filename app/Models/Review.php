<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'service_id',
        'client_id',
        'provider_id',
        'rating',
        'comment',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
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

    protected static function boot()
    {
        parent::boot();

        static::created(function ($review) {
            // Update service rating
            $service = $review->service;
            $avgRating = $service->reviews()->avg('rating');
            $service->update([
                'rating' => $avgRating,
                'reviews_count' => $service->reviews()->count(),
            ]);

            // Update provider rating
            $provider = $review->provider;
            $provider->updateRating();
        });

        static::updated(function ($review) {
            // Update service rating
            $service = $review->service;
            $avgRating = $service->reviews()->avg('rating');
            $service->update([
                'rating' => $avgRating,
                'reviews_count' => $service->reviews()->count(),
            ]);

            // Update provider rating
            $provider = $review->provider;
            $provider->updateRating();
        });

        static::deleted(function ($review) {
            // Update service rating
            $service = $review->service;
            if ($service) {
                $avgRating = $service->reviews()->avg('rating');
                $service->update([
                    'rating' => $avgRating ?: 0,
                    'reviews_count' => $service->reviews()->count(),
                ]);
            }

            // Update provider rating
            $provider = $review->provider;
            if ($provider) {
                $provider->updateRating();
            }
        });
    }
}
