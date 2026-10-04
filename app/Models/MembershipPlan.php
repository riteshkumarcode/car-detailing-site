<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'slug',
        'price',
        'billing_frequency',
        'duration_days',
        'included_services',
        'category_discounts',
        'features',
        'benefits_description',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'included_services' => 'array',
        'category_discounts' => 'array',
        'features' => 'array',
    ];

    public function memberships()
    {
        return $this->hasMany(CustomerMembership::class);
    }
}
