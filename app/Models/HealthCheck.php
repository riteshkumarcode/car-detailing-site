<?php

namespace App\Models;

use App\Domain\Normalizers\RegistrationNormalizer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class HealthCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'vehicle_id',
        'customer_id',
        'booking_id',
        'user_id',
        'check_number',
        'check_date',
        'overall_score',
        'protection_type',
        'category_scores',
        'weights_snapshot',
        'checklist_data',
        'recommended_today',
        'recommended_later',
        'technician_notes',
        'share_token',
    ];

    protected $casts = [
        'check_date'        => 'date',
        'overall_score'     => 'integer',
        'category_scores'   => 'array',
        'weights_snapshot'  => 'array',
        'checklist_data'    => 'array',
        'recommended_today' => 'array',
        'recommended_later' => 'array',
    ];

    public static function getMandatoryDisclaimer(): string
    {
        return Setting::get(
            'health_check.disclaimer',
            'The Drive Health Score describes cosmetic condition only. It is not a certified mechanical, roadworthiness or safety inspection.'
        );
    }

    protected static function booted(): void
    {
        static::creating(function (HealthCheck $check) {
            if (empty($check->check_number)) {
                $dateStr = date('Ymd');
                $randomSuffix = strtoupper(Str::random(4));
                $check->check_number = "TDC-HC-{$dateStr}-{$randomSuffix}";
            }

            if (empty($check->share_token)) {
                $check->share_token = Str::random(32);
            }
        });
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function getFormattedPlateAttribute(): string
    {
        return $this->vehicle ? $this->vehicle->formatted_plate : '';
    }

    public function getShareUrl(): string
    {
        return route('health-report.show', ['token' => $this->share_token]);
    }

    public function getWhatsAppShareUrl(): string
    {
        $phone = $this->customer ? $this->customer->mobile : '';
        $plate = $this->formatted_plate;
        $url = $this->getShareUrl();

        $message = "Hi {$this->customer->name}, your Digital Car Health Check for *{$plate}* is ready!\n\nOverall Score: *{$this->overall_score} / 100*\nView your photographic report & recommendations:\n{$url}\n\n— The Drive Clinic Jammu";

        return 'https://wa.me/91' . preg_replace('/[^0-9]/', '', $phone) . '?text=' . urlencode($message);
    }
}
