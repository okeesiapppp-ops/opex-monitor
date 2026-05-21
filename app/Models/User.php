<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // admin, vendor, planner
        'status',
        'phone',
        'company',
        'last_login'
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
            'last_login' => 'datetime',
        ];
    }

    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Check if user is vendor (UT Part)
    public function isVendor()
    {
        return $this->role === 'vendor';
    }

    // Check if user is planner (PAMA)
    public function isPlanner()
    {
        return $this->role === 'planner';
    }

    // Relasi ke Planner
    public function planner()
    {
        return $this->hasOne(TbPlanner::class, 'user_id', 'id');
    }

    // Relasi ke Vendor
    public function vendor()
    {
        return $this->hasOne(TbVendor::class, 'user_id', 'id');
    }
}