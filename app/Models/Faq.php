<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'question',
        'answer',
        'show_on_home',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'show_on_home' => 'boolean',
        'is_active' => 'boolean',
    ];
}
