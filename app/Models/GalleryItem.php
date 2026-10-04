<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'title',
        'area',
        'problem_description',
        'service_name',
        'car_model',
        'before_image_path',
        'after_image_path',
        'is_featured',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];
}
