<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'img',
        'email',
        'password',
        'role_id',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        // 'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function assets()
    {
        return $this->belongsToMany(Asset::class);
    }


    public function hasPermission($permissionName)
    {
        return $this->role && $this->role->permissions->contains('name', $permissionName);
    }
    public function scopeFilterUser($query, $Filter = [])
    {
        $query->with('role');
        if (!empty($Filter['equipment_filter'])) {
            $query->with('assets')->whereHas('assets', function ($q) use ($Filter) {
                $q->where('assets.id', $Filter['equipment_filter']);
            });
        }
        if (!empty($Filter['name_filter'])) {
            $query->where('name', 'like', '%' . $Filter['name_filter'] . '%');
        }
        if (!empty($Filter['email_filter'])) {
            $query->where('email', 'like', '%' . $Filter['email_filter'] . '%');
        }
        if (!empty($Filter['role_filter'])) {
            $query->where('role_id', $Filter['role_filter']);
        }
        return $query;
    }
}
