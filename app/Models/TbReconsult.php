<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbReconsult extends Model
{
    use HasFactory;
    
    protected $table = 'tb_reconsult';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'backlog_id',
        'tanggal_konsultasi',
        'masalah',
        'solusi',
        'status', // open, in_progress, resolved, closed
        'pic_vendor',
        'pic_planner',
        'keterangan',
        'priority', // high, medium, low
        'resolved_date',
        'created_by'
    ];

    protected $casts = [
        'tanggal_konsultasi' => 'datetime',
        'resolved_date' => 'datetime',
    ];
    
    public function backlog()
    {
        return $this->belongsTo(TbBacklog::class, 'backlog_id', 'id');
    }

    public function vendorPic()
    {
        return $this->belongsTo(User::class, 'pic_vendor', 'id');
    }

    public function plannerPic()
    {
        return $this->belongsTo(User::class, 'pic_planner', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // Scope untuk status open
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    // Scope untuk status resolved
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    // Scope untuk high priority
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    // Check if resolved
    public function isResolved()
    {
        return in_array($this->status, ['resolved', 'closed']);
    }

    // Get resolution time (in days)
    public function getResolutionTime()
    {
        if (!$this->resolved_date) return null;
        
        return $this->tanggal_konsultasi->diffInDays($this->resolved_date);
    }

    // Get status badge
    public function getStatusBadge()
    {
        return match($this->status) {
            'open' => 'danger',
            'in_progress' => 'warning',
            'resolved' => 'success',
            'closed' => 'secondary',
            default => 'dark'
        };
    }

    // Get priority badge
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