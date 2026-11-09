<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'unit_code',
        'phone',
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
        ];
    }

    public function isDirektur(): bool
    {
        return $this->role === 'direktur';
    }

    public function isBendahara(): bool
    {
        return $this->role === 'bendahara';
    }

    public function isAdminUnit(): bool
    {
        return $this->role === 'admin_unit';
    }

    public function canAccessUnit(?string $unitCode): bool
    {
        if (in_array($this->role, ['direktur', 'bendahara'])) {
            return true;
        }
        return $this->unit_code === $unitCode;
    }

    public function getHomeRoute(): string
    {
        if (in_array($this->role, ['direktur', 'bendahara'])) {
            return route('dashboard');
        }

        return match ($this->unit_code) {
            'cfd' => route('cfd.index'),
            'pamsimas' => route('pamsimas.index'),
            default => route('dashboard'),
        };
    }

    public function getUnitNameAttribute(): string
    {
        return match ($this->unit_code) {
            'cfd' => 'Unit CFD & Pasar UMKM Minggu',
            'pamsimas' => 'Unit Pamsimas Air Bersih',
            default => 'Kantor Pengelola BUMDes',
        };
    }
}
