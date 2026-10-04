<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CustomerMembership extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'branch_id',
        'customer_id',
        'vehicle_id',
        'membership_plan_id',
        'plan_name',
        'price_paid',
        'payment_method',
        'starts_at',
        'expires_at',
        'status',
        'remaining_services',
        'category_discounts',
        'notes',
        'created_by_user_id',
    ];

    protected $casts = [
        'price_paid'         => 'decimal:2',
        'starts_at'          => 'datetime',
        'expires_at'         => 'datetime',
        'remaining_services' => 'array',
        'category_discounts' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'plan_name',
                'status',
                'price_paid',
                'payment_method',
                'expires_at',
                'remaining_services',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
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

    public function plan(): BelongsTo
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(MembershipRedemption::class)->orderByDesc('redeemed_at');
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' && Carbon::now()->lte($this->expires_at);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->status === 'expired' || Carbon::now()->gt($this->expires_at);
    }

    public function getRemainingServiceCount(int $serviceId): int
    {
        if (empty($this->remaining_services)) {
            return 0;
        }

        foreach ($this->remaining_services as $service) {
            if ((int) ($service['service_id'] ?? 0) === $serviceId) {
                return (int) ($service['remaining_count'] ?? 0);
            }
        }

        return 0;
    }

    public function getCategoryDiscount(string $category): float
    {
        if (empty($this->category_discounts)) {
            return 0.0;
        }

        $normalized = strtolower(trim($category));
        foreach ($this->category_discounts as $cat => $discount) {
            if (strtolower(trim($cat)) === $normalized) {
                return (float) $discount;
            }
        }

        return 0.0;
    }

    public function getTotalRemainingServicesCountAttribute(): int
    {
        if (empty($this->remaining_services)) {
            return 0;
        }

        $total = 0;
        foreach ($this->remaining_services as $service) {
            $total += (int) ($service['remaining_count'] ?? 0);
        }

        return $total;
    }
}
