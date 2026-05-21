<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbVendor extends Model
{
    use HasFactory;

    protected $table = 'tb_vendor';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'code',
        'phone',
        'email',
        'address',
        'is_active',
        'status',
        'user_id',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke BacklogDaily
    public function backlogDaily()
    {
        return $this->hasMany(TbBacklogDaily::class, 'vendor_id', 'id');
    }

    // Relasi ke Orders
    public function orders()
    {
        return $this->hasMany(TbOrder::class, 'vendor_id', 'id');
    }

    // Relasi ke Reconciliation
    public function reconciliations()
    {
        return $this->hasMany(TbReconciliation::class, 'vendor_id', 'id');
    }

    // Scope untuk vendor aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // Check if vendor is active
    public function isActive()
    {
        return $this->is_active == 1;
    }

    // Get fulfillment rate (%)
    public function getFulfillmentRate()
    {
        $total = $this->backlogDaily()->count();
        if ($total === 0) return 0;
        $completed = $this->backlogDaily()->where('is_ready', true)->count();
        return round(($completed / $total) * 100, 2);
    }
}