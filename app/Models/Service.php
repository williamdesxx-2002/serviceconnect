<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'price_type',
        'duration',
        'images',
        'tags',
        'latitude',
        'longitude',
        'address',
        'city',
        'country',
        'neighborhood',
        'status',
        'is_active',
        'rating',
        'reviews_count',
        'is_reported',
        'report_reason',
        'report_description',
        'reported_at',
        'reported_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'images' => 'array',
        'tags' => 'json',
        'is_active' => 'boolean',
        'is_reported' => 'boolean',
        'rating' => 'decimal:2',
        'reported_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'approved');
    }

    public function scopeNearby($query, $latitude, $longitude, $radius = 10)
    {
        return $query->selectRaw('*,
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance
        ', [$latitude, $longitude, $latitude])
        ->having('distance', '<=', $radius)
        ->orderBy('distance');
    }

    public function getDistanceFrom($latitude, $longitude)
    {
        $earthRadius = 6371; // km

        $latFrom = deg2rad($latitude);
        $lonFrom = deg2rad($longitude);
        $latTo = deg2rad($this->latitude);
        $lonTo = deg2rad($this->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Get the first image URL
     */
    public function getFirstImageAttribute()
    {
        $images = $this->images;
        if (is_string($images)) {
            $images = json_decode($images, true) ?? [];
        }
        
        if (!empty($images) && is_array($images)) {
            return asset('storage/' . $images[0]);
        }
        
        return null;
    }

    /**
     * Get all images URLs
     */
    public function getImageUrlsAttribute()
    {
        $images = $this->images;
        if (is_string($images)) {
            $images = json_decode($images, true) ?? [];
        }
        
        if (empty($images) || !is_array($images)) {
            return [];
        }
        
        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $images);
    }

    /**
     * Set images attribute as JSON
     */
    public function setImagesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['images'] = json_encode($value);
        } else {
            $this->attributes['images'] = $value;
        }
    }

    /**
     * Get images attribute as array
     */
    public function getImagesAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        
        return $value ?? [];
    }

    /**
     * Calculate average rating from reviews
     */
    public function averageRating()
    {
        if ($this->reviews_count > 0) {
            return $this->reviews()->avg('rating') ?? 0;
        }
        
        return 0;
    }
}
