<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingStatusLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'from_status',
        'to_status',
        'user_id',
        'reason',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
