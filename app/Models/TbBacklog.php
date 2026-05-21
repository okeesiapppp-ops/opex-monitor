<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TbBacklog extends Model
{
    use HasFactory;
    
    protected $table = 'tb_backlog';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'unit_number',
        'model',
        'part_number',
        'part_name',
        'qty',
        'satuan',
        'keterangan',
        'status', // pending, in_progress, complete, cancelled
        'vendor',
        'eta',
        'location',
        'ref_penarikan',
        'stock_status', // available, on_order, backorder
        'priority', // high, medium, low
        'completion_date',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'eta' => 'date',
        'completion_date' => 'date',
        'qty' => 'integer',
    ];
    
    // Relasi ke BacklogDaily
    public function backlogDaily()
    {
        return $this->hasMany(TbBacklogDaily::class, 'backlog_id', 'id');
    }
    
    // Relasi ke Order
    public function orders()
    {
        return $this->hasMany(TbOrder::class, 'backlog_id', 'id');
    }

    // Relasi ke Reconciliation
    public function reconciliations()
    {
        return $this->hasMany(TbReconciliation::class, 'backlog_id', 'id');
    }

    // Relasi ke Reconsult
    public function reconsults()
    {
        return $this->hasMany(TbReconsult::class, 'backlog_id', 'id');
    }

    // Relasi ke User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // Relasi ke User (updater)
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    // Scope untuk filter status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeComplete($query)
    {
        return $query->where('status', 'complete');
    }

    // Scope untuk filter overdue (lewat ETA)
    public function scopeOverdue($query)
    {
        return $query->where('eta', '<', Carbon::now())
                     ->whereNotIn('status', ['complete', 'cancelled']);
    }

    // Check if backlog is overdue
    public function isOverdue()
    {
        if (!$this->eta || in_array($this->status, ['complete', 'cancelled'])) {
            return false;
        }
        return Carbon::parse($this->eta)->isPast();
    }

    // Get status badge color
    public function getStatusBadge()
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'complete' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    // Get priority badge color
    public function getPriorityBadge()
    {
        return match($this->priority) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'info',
            default => 'secondary'
        };
    }
}