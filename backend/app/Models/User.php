<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $visible = [
        'id',
        'name',
        'email',
        'roles',
        'created_at',
        'updated_at',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $slug): bool
    {
        return $this->roles->contains(fn ($r) => $r->slug === $slug);
    }

    public function hasAnyRole(array $slugs): bool
    {
        return $this->roles->contains(fn ($r) => in_array($r->slug, $slugs, true));
    }

    public function hasPermission(string $slug): bool
    {
        // Requires roles+permissions to be loaded or will lazy-load.
        foreach ($this->roles as $role) {
            $role->loadMissing('permissions');
            if ($role->permissions->contains(fn ($p) => $p->slug === $slug)) {
                return true;
            }
        }
        return false;
    }
}
