<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'service_category_id',
        'name',
        'slug',
        'short_description',
        'full_description',
        'target_problem',
        'who_its_for',
        'duration_minutes',
        'price_hatchback',
        'price_sedan',
        'price_suv',
        'price_other',
        'is_price_on_inspection',
        'whats_included',
        'process_steps',
        'faqs',
        'add_ons',
        'image_path',
        'before_image_path',
        'after_image_path',
        'sort_order',
        'is_active',
        'show_on_home',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'price_hatchback' => 'decimal:2',
        'price_sedan' => 'decimal:2',
        'price_suv' => 'decimal:2',
        'price_other' => 'decimal:2',
        'is_price_on_inspection' => 'boolean',
        'is_active' => 'boolean',
        'show_on_home' => 'boolean',
        'whats_included' => 'array',
        'process_steps' => 'array',
        'faqs' => 'array',
        'add_ons' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function getStartingPriceAttribute(): ?float
    {
        if ($this->is_price_on_inspection) {
            return null;
        }

        $prices = array_filter([
            $this->price_hatchback,
            $this->price_sedan,
            $this->price_suv,
        ], fn ($p) => !is_null($p) && $p > 0);

        return !empty($prices) ? (float)min($prices) : null;
    }

    public function getPriceForType(string $type): ?float
    {
        return match (strtolower($type)) {
            'hatchback' => $this->price_hatchback ? (float)$this->price_hatchback : null,
            'sedan'     => $this->price_sedan ? (float)$this->price_sedan : null,
            'suv'       => $this->price_suv ? (float)$this->price_suv : null,
            default     => $this->price_other ? (float)$this->price_other : ($this->price_sedan ? (float)$this->price_sedan : null),
        };
    }

    public function getPriceForVehicleType(string $type): ?float
    {
        return $this->getPriceForType($type);
    }
}
