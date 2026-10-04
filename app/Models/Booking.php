<?php

namespace App\Models;

use App\Domain\Normalizers\RegistrationNormalizer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'booking_number',
        'customer_id',
        'vehicle_id',
        'service_id',
        'service_name',
        'vehicle_type',
        'price',
        'booking_date',
        'booking_time',
        'duration_minutes',
        'slots_count',
        'name',
        'mobile',
        'email',
        'registration_number',
        'make_model',
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'price' => 'decimal:2',
        'duration_minutes' => 'integer',
        'slots_count' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->booking_number)) {
                $dateStr = date('Ymd');
                $randomSuffix = strtoupper(Str::random(4));
                $booking->booking_number = "TDC-BK-{$dateStr}-{$randomSuffix}";
            }

            if ($booking->registration_number) {
                $booking->registration_number = RegistrationNormalizer::normalize($booking->registration_number);
            }
        });

        static::updating(function (Booking $booking) {
            if ($booking->isDirty('registration_number')) {
                $booking->registration_number = RegistrationNormalizer::normalize($booking->registration_number);
            }

            if ($booking->isDirty('status')) {
                BookingStatusLog::create([
                    'booking_id' => $booking->id,
                    'from_status' => $booking->getOriginal('status'),
                    'to_status' => $booking->status,
                    'user_id' => auth()->id() ?? null,
                    'reason' => 'Status changed via system or staff action',
                ]);
            }
        });

        static::created(function (Booking $booking) {
            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'from_status' => null,
                'to_status' => $booking->status,
                'user_id' => auth()->id() ?? null,
                'reason' => 'Initial booking created',
            ]);
        });
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(BookingStatusLog::class)->orderBy('id', 'desc');
    }

    public function getFormattedPlateAttribute(): string
    {
        return RegistrationNormalizer::format($this->registration_number);
    }

    public function isActive(): bool
    {
        return !in_array($this->status, ['cancelled', 'no_show']);
    }
}
