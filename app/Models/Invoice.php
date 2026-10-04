<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use RuntimeException;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Invoice extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'branch_id',
        'invoice_number',
        'financial_year',
        'sequence_number',
        'customer_id',
        'vehicle_id',
        'booking_id',
        'issued_by_user_id',
        'status',
        'cancellation_reason',
        'cancelled_by_user_id',
        'cancelled_at',
        'reissued_invoice_id',
        'payment_method',
        'payment_reference',
        'staff_notes',
        'assigned_staff_ids',
        'subtotal',
        'discount_type',
        'discount_value',
        'discount_amount',
        'discount_reason',
        'is_gst_enabled',
        'gstin',
        'cgst_rate',
        'sgst_rate',
        'cgst_amount',
        'sgst_amount',
        'total_tax',
        'total_amount',
        'share_token',
    ];

    protected $casts = [
        'assigned_staff_ids' => 'array',
        'is_gst_enabled'     => 'boolean',
        'subtotal'           => 'decimal:2',
        'discount_value'     => 'decimal:2',
        'discount_amount'    => 'decimal:2',
        'cgst_rate'          => 'decimal:2',
        'sgst_rate'          => 'decimal:2',
        'cgst_amount'        => 'decimal:2',
        'sgst_amount'        => 'decimal:2',
        'total_tax'          => 'decimal:2',
        'total_amount'       => 'decimal:2',
        'cancelled_at'       => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'invoice_number',
                'status',
                'cancellation_reason',
                'reissued_invoice_id',
                'discount_amount',
                'discount_reason',
                'total_amount',
                'payment_method',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            if (empty($invoice->share_token)) {
                $invoice->share_token = Str::random(40);
            }
        });

        static::updating(function (Invoice $invoice) {
            // Immutability rule: An issued invoice cannot have financial or structural values modified.
            // Only cancellation metadata or reissue linkage may be updated.
            if ($invoice->getOriginal('status') === 'issued' && $invoice->isDirty([
                'invoice_number',
                'financial_year',
                'sequence_number',
                'customer_id',
                'vehicle_id',
                'subtotal',
                'discount_type',
                'discount_value',
                'discount_amount',
                'discount_reason',
                'is_gst_enabled',
                'cgst_rate',
                'sgst_rate',
                'cgst_amount',
                'sgst_amount',
                'total_tax',
                'total_amount',
            ])) {
                throw new RuntimeException("Invoice {$invoice->invoice_number} is immutable and cannot be modified once issued. Cancel and reissue instead.");
            }

            if ($invoice->getOriginal('status') === 'cancelled' && $invoice->isDirty(['status']) && $invoice->status !== 'cancelled') {
                throw new RuntimeException("A cancelled invoice cannot be un-cancelled.");
            }
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

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by_user_id');
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by_user_id');
    }

    public function reissuedInvoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'reissued_invoice_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function scopeIssued($query)
    {
        return $query->where('status', 'issued');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function getIsIssuedAttribute(): bool
    {
        return $this->status === 'issued';
    }

    public function getIsCancelledAttribute(): bool
    {
        return $this->status === 'cancelled';
    }

    public function getFormattedPlateAttribute(): string
    {
        return $this->vehicle?->formatted_plate ?? '';
    }

    public function getAssignedStaffNamesAttribute(): array
    {
        if (empty($this->assigned_staff_ids)) {
            return [];
        }

        return User::whereIn('id', $this->assigned_staff_ids)->pluck('name')->toArray();
    }
}
