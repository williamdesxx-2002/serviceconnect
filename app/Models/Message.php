<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'booking_id',
        'content',
        'type',
        'file_path',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeBetween($query, $user1, $user2)
    {
        return $query->where(function ($q) use ($user1, $user2) {
            $q->where(function ($subQuery) use ($user1, $user2) {
                $subQuery->where('sender_id', $user1)
                         ->where('receiver_id', $user2);
            })->orWhere(function ($subQuery) use ($user1, $user2) {
                $subQuery->where('sender_id', $user2)
                         ->where('receiver_id', $user1);
            });
        });
    }
}
