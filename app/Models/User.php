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
        'role',
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

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        // Admin สามารถเข้าถึงทุก role ได้ (ยกเว้น visitor)
        if ($this->isAdmin() && $role !== 'visitor') {
            return true;
        }
        
        return $this->role === $role;
    }

    /**
     * Check if user can access specific route/page
     */
    public function canAccess(string $permission): bool
    {
        // Admin สามารถเข้าถึงทุกอย่างได้
        if ($this->isAdmin()) {
            return true;
        }

        // สำหรับ user ทั่วไป ตรวจสอบตาม role
        switch ($permission) {
            case 'admin_panel':
                return $this->isAdmin();
            case 'user_dashboard':
                return $this->isUser() || $this->isAdmin();
            case 'personal_info':
                return $this->isUser() || $this->isAdmin();
            case 'add_project':
                return $this->isUser() || $this->isAdmin();
            case 'documentation':
                return $this->isUser() || $this->isAdmin();
            case 'statistics':
                return $this->isUser() || $this->isAdmin();
            case 'dashboard':
                return $this->isUser() || $this->isAdmin();
            default:
                return false;
        }
    }

    /**
     * Get analytics for this user
     */
    public function analytics()
    {
        return $this->hasMany(Analytics::class);
    }

    /**
     * Get all personal info entries for this user
     */
    public function personalInfos()
    {
        return $this->hasMany(PersonalInfo::class);
    }

    /**
     * Get notifications for this user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
