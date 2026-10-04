<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'branch_id',
        'name',
        'email',
        'phone',
        'role',
        'is_active',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active && ($this->hasRole(['owner', 'manager', 'staff']) || in_array($this->role, ['owner', 'manager', 'staff']));
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner' || $this->hasRole('owner');
    }

    public function isManager(): bool
    {
        return $this->role === 'manager' || $this->hasRole('manager');
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff' || $this->hasRole('staff');
    }
}
