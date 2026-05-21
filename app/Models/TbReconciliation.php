<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbReconciliation extends Model
{
    use HasFactory;
    
    protected $table = 'tb_reconciliation';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'tanggal',
        'backlog_id',
        'part_number',
        'part_name',
        'qty_ordered',
        'qty_received',
        'qty_difference', // selisih
        'status', // match, mismatch, partial
        'keterangan',
        'vendor_id',
        'reconcile_by',
        'approved_by',
        'created_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'qty_ordered' => 'integer',
        'qty_received' => 'integer',
        'qty_difference' => 'integer',
    ];
    
    public function backlog()
    {
        return $this->belongsTo(TbBacklog::class, 'backlog_id', 'id');
    }
    
    public function vendor()
    {
        return $this->belongsTo(TbVendor::class, 'vendor_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function reconciler()
    {
        return $this->belongsTo(User::class, 'reconcile_by', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    // Scope untuk data yang match
    public function scopeMatch($query)
    {
        return $query->where('status', 'match');
    }

    // Scope untuk data yang mismatch
    public function scopeMismatch($query)
    {
        return $query->where('status', 'mismatch');
    }

    // Calculate difference automatically
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->qty_difference = $model->qty_received - $model->qty_ordered;
            
            // Auto determine status
            if ($model->qty_difference === 0) {
                $model->status = 'match';
            } elseif ($model->qty_received > 0 && $model->qty_received < $model->qty_ordered) {
                $model->status = 'partial';
            } else {
                $model->status = 'mismatch';
            }
        });
    }

    // Get status badge
    public function getStatusBadge()
    {
        return match($this->status) {
            'match' => 'success',
            'partial' => 'warning',
            'mismatch' => 'danger',
            default => 'secondary'
        };
    }

    // Check if has discrepancy
    public function hasDiscrepancy()
    {
        return $this->qty_difference !== 0;
    }
}