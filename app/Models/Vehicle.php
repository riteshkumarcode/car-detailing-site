<?php

namespace App\Models;

use App\Domain\Normalizers\RegistrationNormalizer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'customer_id',
        'registration_number',
        'make',
        'model',
        'variant',
        'vehicle_type',
        'colour',
        'photos',
        'notes',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (Vehicle $vehicle) {
            if ($vehicle->registration_number) {
                $vehicle->registration_number = RegistrationNormalizer::normalize($vehicle->registration_number);
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class)->orderByDesc('created_at');
    }

    public function healthChecks(): HasMany
    {
        return $this->hasMany(HealthCheck::class)->orderByDesc('check_date');
    }

    public function latestHealthCheck()
    {
        return $this->hasOne(HealthCheck::class)->latestOfMany();
    }

    public function latestInvoice()
    {
        return $this->hasOne(Invoice::class)->where('status', 'issued')->latestOfMany('created_at');
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(CustomerMembership::class)->orderByDesc('created_at');
    }

    public function activeMembership()
    {
        return $this->hasOne(CustomerMembership::class)
            ->where('status', 'active')
            ->where('expires_at', '>=', now())
            ->latestOfMany();
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(MembershipRedemption::class)->orderByDesc('redeemed_at');
    }

    public function getIsMemberAttribute(): bool
    {
        return $this->activeMembership !== null;
    }

    public function getActivePlanNameAttribute(): ?string
    {
        return $this->activeMembership?->plan_name;
    }

    public function getFormattedPlateAttribute(): string
    {
        return RegistrationNormalizer::format($this->registration_number);
    }

    public function getTotalVisitsAttribute(): int
    {
        return max(
            $this->bookings()->where('status', 'completed')->count(),
            $this->invoices()->where('status', 'issued')->count()
        );
    }

    public function getTotalSpendAttribute(): float
    {
        $invoiceSum = (float) $this->invoices()->where('status', 'issued')->sum('total_amount');
        if ($invoiceSum > 0) {
            return $invoiceSum;
        }

        return (float) $this->bookings()->where('status', 'completed')->sum('price');
    }

    public function getFirstVisitAttribute(): ?string
    {
        $first = $this->invoices()->where('status', 'issued')->orderBy('created_at')->first()
            ?: $this->bookings()->where('status', 'completed')->orderBy('booking_date')->first();

        if ($first instanceof Invoice) {
            return $first->created_at->format('d M Y');
        }

        return $first?->booking_date ? $first->booking_date->format('d M Y') : null;
    }

    public function getLastVisitAttribute(): ?string
    {
        $last = $this->invoices()->where('status', 'issued')->orderByDesc('created_at')->first()
            ?: $this->bookings()->where('status', 'completed')->orderByDesc('booking_date')->first();

        if ($last instanceof Invoice) {
            return $last->created_at->format('d M Y');
        }

        return $last?->booking_date ? $last->booking_date->format('d M Y') : null;
    }
}
