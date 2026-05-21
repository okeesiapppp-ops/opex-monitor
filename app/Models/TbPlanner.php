<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbPlanner extends Model
{
    use HasFactory;
    
    protected $table = 'tb_planner';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'user_id',
        'is_active',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke BacklogDaily
    public function backlogDaily()
    {
        return $this->hasMany(TbBacklogDaily::class, 'planner_id', 'id');
    }
    
    // Relasi ke Orders
    public function orders()
    {
        return $this->hasMany(TbOrder::class, 'planner_id', 'id');
    }

    // Scope untuk planner aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // Check if planner is active
    public function isActive()
    {
        return $this->is_active == 1;
    }
}