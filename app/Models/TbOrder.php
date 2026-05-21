<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbOrder extends Model
{
    use HasFactory;
    
    protected $table = 'tb_order';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'backlog_id',
        'order_number',
        'tanggal_order',
        'part_number',
        'part_name',
        'qty',
        'status', // ordered, confirmed, shipped, received, cancelled
        'vendor_id',
        'planner_id',
        'po_number',
        'wr_number', // Work Request number
        'expected_arrival',
        'actual_arrival',
        'keterangan',
        'tracking_number',
        'created_by'
    ];

    protected $casts = [
        'tanggal_order' => 'date',
        'expected_arrival' => 'date',
        'actual_arrival' => 'date',
        'qty' => 'integer',
    ];
    
    public function backlog()
    {
        return $this->belongsTo(TbBacklog::class, 'backlog_id', 'id');
    }
    
    public function vendor()
    {
        return $this->belongsTo(TbVendor::class, 'vendor_id', 'id');
    }
    
    public function planner()
    {
        return $this->belongsTo(TbPlanner::class, 'planner_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // Scope untuk filter by status
    public function scopeOrdered($query)
    {
        return $query->where('status', 'ordered');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }

    // Generate order number otomatis
    public static function generateOrderNumber()
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', today())
                         ->orderBy('id', 'desc')
                         ->first();
        
        $sequence = $lastOrder ? (int)substr($lastOrder->order_number, -4) + 1 : 1;
        
        return 'ORD-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    // Check if order is delayed
    public function isDelayed()
    {
        if (!$this->expected_arrival || $this->status === 'received') {
            return false;
        }
        return now()->isAfter($this->expected_arrival);
    }

    // Get status badge
    public function getStatusBadge()
    {
        return match($this->status) {
            'ordered' => 'warning',
            'confirmed' => 'info',
            'shipped' => 'primary',
            'received' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }
}