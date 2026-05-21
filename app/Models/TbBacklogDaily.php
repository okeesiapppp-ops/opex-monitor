<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbBacklogDaily extends Model
{
    use HasFactory;

    protected $table = 'tb_backlog_daily';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'backlog_id',
        'vendor_id',
        'planner_id',
        'tanggal',
        'is_ready',
        'keterangan',
        'status',
        // Kolom BCS
        'reservation_number',
        'reservation_item',
        'mo_description',
        'equipment',
        'egi',
        'part_number',
        'npn',
        'manufacture',
        'qty',
        'unit',
        'plan_date',
        'jarak_eksekusi',
        'tanggal_bcs',
        'remarks',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'plan_date'   => 'date',
        'tanggal_bcs' => 'date',
        'qty'         => 'integer',
        'is_ready'    => 'boolean',
    ];

    // Relasi ke Backlog
    public function backlog()
    {
        return $this->belongsTo(TbBacklog::class, 'backlog_id', 'id');
    }

    // Relasi ke Planner
    public function planner()
    {
        return $this->belongsTo(TbPlanner::class, 'planner_id', 'id');
    }

    // Relasi ke Vendor
    public function vendor()
    {
        return $this->belongsTo(TbVendor::class, 'vendor_id', 'id');
    }

    // Scope data hari ini
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', today());
    }

    // Scope data ready
    public function scopeReady($query)
    {
        return $query->where('is_ready', true);
    }

    // Scope data pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope data outstanding
    public function scopeOutstanding($query)
    {
        return $query->where('status', 'outstanding');
    }

    // Scope data complete
    public function scopeComplete($query)
    {
        return $query->where('status', 'complete');
    }

    // Scope by vendor
    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    // Scope by planner
    public function scopeByPlanner($query, $plannerId)
    {
        return $query->where('planner_id', $plannerId);
    }

    // Get status badge color
    public function getStatusBadge()
    {
        return match($this->status) {
            'complete'    => 'success',
            'outstanding' => 'warning',
            'pending'     => 'secondary',
            default       => 'dark'
        };
    }
}