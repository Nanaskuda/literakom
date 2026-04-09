<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;


class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'no_id',
        'name',
        'kelas',
        'jurusan',
        'username',
        'email',
        'password',
        'no_telepon',
        'role',
        'book_count',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        ];


    public function borrowings(){
        return $this->hasMany(Borrowing::class);
    }
    public function favorites(){
        return $this->hasMany(Favorite::class);
    }
    public function reviews(){
        return $this->hasMany(Review::class);
    }


    public function activeLoans()
    {
        return $this->borrowings()->where('status', 'dipinjam');
        }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $last = static::max('id') ?? 0;
            $user->no_id = 'LIB-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
            });
            }

    const ROLE_ADMIN = 'admin';

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }
}
