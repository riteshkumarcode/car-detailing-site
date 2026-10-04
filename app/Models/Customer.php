<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'mobile',
        'email',
        'area',
        'referral_source',
        'referral_code',
        'tags',
        'date_joined',
        'notes',
    ];

    protected $casts = [
        'date_joined' => 'date',
        'tags' => 'array',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class)->orderByDesc('created_at');
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

    public function communicationLogs(): HasMany
    {
        return $this->hasMany(CommunicationLog::class)->orderBy('logged_at', 'desc');
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

    public function getAverageBillAttribute(): float
    {
        $visits = $this->total_visits;
        return $visits > 0 ? (float) ($this->total_spend / $visits) : 0.0;
    }

    public function getFirstVisitAttribute(): ?string
    {
        $first = $this->bookings()->where('status', 'completed')->orderBy('booking_date')->first();
        return $first ? $first->booking_date->format('d M Y') : null;
    }

    public function getLastVisitAttribute(): ?string
    {
        $last = $this->bookings()->where('status', 'completed')->orderByDesc('booking_date')->first();
        return $last ? $last->booking_date->format('d M Y') : null;
    }
}
