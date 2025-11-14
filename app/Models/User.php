<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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
        'email',
        'password',
        'image',
        'role_id',
        'invisible'
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
        return $this->belongsTo(Role::class, 'role_id');
    }
    // Helper linh hoạt: nhận 'admin' hoặc ['admin','manager']
    public function hasRole(string|array $roles): bool
    {
        if (!$this->role) return false;
        $current = $this->role->name ?? $this->role->label;

        if (is_array($roles)) {
            return in_array($current, $roles, true);
        }
        return $current === $roles;
    }

    // Tiện ích
    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }
    
    public function scientificProfile()
    {
        return $this->hasOne(ScientificProfile::class);
    }
    public function personalInfo()
    {
        return $this->hasOne(PersonalInfo::class);
    }
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
