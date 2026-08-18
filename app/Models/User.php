<?php

namespace App\Models;

// 1. Import class FilamentUser & Panel
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// 2. Tambah 'implements FilamentUser'
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // 3. Tambah fungsi canAccessPanel untuk benarkan akses
    public function canAccessPanel(Panel $panel): bool
    {
        return true; // Membenarkan semua user berdaftar masuk ke panel
    }
}