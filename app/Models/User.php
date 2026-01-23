<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'whatsapp_number',
        'whatsapp_notifications',
        'bio',
        'avatar',
        'latitude',
        'longitude',
        'address',
        'city',
        'country',
        'is_verified',
        'is_active',
        'last_login_at',
        'provider',
        'provider_id',
        'rating',
        'reviews_count',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'decimal:2',
        'reviews_count' => 'integer',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function clientBookings()
    {
        return $this->hasMany(Booking::class, 'client_id');
    }

    public function providerBookings()
    {
        return $this->hasMany(Booking::class, 'provider_id');
    }

    public function receivedBookings()
    {
        return $this->providerBookings();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'provider_id');
    }

    public function receivedReviews()
    {
        return $this->reviews();
    }

    public function givenReviews()
    {
        return $this->hasMany(Review::class, 'client_id');
    }

    public function updateRating()
    {
        $avgRating = $this->reviews()->avg('rating');
        $reviewsCount = $this->reviews()->count();
        
        $this->update([
            'rating' => $avgRating ?: 0,
            'reviews_count' => $reviewsCount,
        ]);
    }

    public function getRatingStarsAttribute()
    {
        $rating = round($this->rating);
        $stars = '';
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-warning"></i>';
            }
        }
        
        return $stars;
    }

    public function canBeRatedBy(User $user)
    {
        // Un client peut noter un prestataire s'il a une réservation complétée avec lui
        if ($user->isClient() && $this->isProvider()) {
            return $this->providerBookings()
                ->where('client_id', $user->id)
                ->where('status', 'completed')
                ->exists();
        }
        
        return false;
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id')->latest();
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id')->latest();
    }
    
    public function conversations()
    {
        return Message::where('sender_id', $this->id)
                      ->orWhere('receiver_id', $this->id)
                      ->with(['sender', 'receiver'])
                      ->orderBy('created_at', 'desc')
                      ->get()
                      ->groupBy(function($message) {
                          return $message->sender_id === $this->id ? $message->receiver_id : $message->sender_id;
                      });
    }
    
    public function unreadMessagesCount()
    {
        return $this->receivedMessages()->where('is_read', false)->count();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isProvider()
    {
        return $this->role === 'provider';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function totalEarnings()
    {
        return $this->payments()
            ->where('status', 'completed')
            ->sum('amount') ?? 0;
    }

    public function getWhatsappLinkAttribute()
    {
        if (!$this->whatsapp_number) {
            return null;
        }
        
        // Nettoyer le numéro de téléphone (supprimer les espaces, +, etc.)
        $phoneNumber = preg_replace('/[^0-9]/', '', $this->whatsapp_number);
        
        // Générer le lien WhatsApp
        return "https://wa.me/{$phoneNumber}?text=" . urlencode("Bonjour ! Je vous contacte depuis ServiceConnect concernant vos services.");
    }

    public function hasWhatsapp()
    {
        return !empty($this->whatsapp_number);
    }
}
