<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'source',
        'name',
        'mobile',
        'email',
        'registration_number',
        'make_model',
        'vehicle_type',
        'main_concerns',
        'preferred_date',
        'preferred_time',
        'subject',
        'message',
        'plan_interest',
        'status',
        'assigned_to_user_id',
    ];

    protected $casts = [
        'main_concerns' => 'array',
        'preferred_date' => 'date',
    ];

    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }
}
