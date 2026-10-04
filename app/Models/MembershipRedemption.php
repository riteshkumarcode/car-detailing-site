<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MembershipRedemption extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'branch_id',
        'customer_membership_id',
        'customer_id',
        'vehicle_id',
        'booking_id',
        'invoice_id',
        'service_id',
        'service_name',
        'units_redeemed',
        'performed_by_user_id',
        'redeemed_at',
        'notes',
    ];

    protected $casts = [
        'units_redeemed' => 'integer',
        'redeemed_at'    => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'service_name',
                'units_redeemed',
                'redeemed_at',
                'invoice_id',
                'booking_id',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function membership(): BelongsTo
    {
        return $this->belongsTo(CustomerMembership::class, 'customer_membership_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by_user_id');
    }
}
