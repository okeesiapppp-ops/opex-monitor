<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbArchive extends Model
{
    use HasFactory;

    protected $table = 'tb_archive';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'periode',
        'tanggal_archive',
        'total_backlog',
        'total_complete',
        'total_pending',
        'total_outstanding',
        'achievement_percentage',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'tanggal_archive'        => 'date',
        'total_backlog'          => 'integer',
        'total_complete'         => 'integer',
        'total_pending'          => 'integer',
        'total_outstanding'      => 'integer',
        'achievement_percentage' => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(TbDetailArchive::class, 'archive_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function scopeByPeriode($query, $periode)
    {
        return $query->where('periode', $periode);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('periode', 'like', $year . '%');
    }

    // Auto-calculate achievement saat saving
    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if ($model->total_backlog > 0) {
                $model->achievement_percentage = round(
                    ($model->total_complete / $model->total_backlog) * 100, 2
                );
            } else {
                $model->achievement_percentage = 0;
            }
        });
    }

    public function meetsTarget()
    {
        return $this->achievement_percentage >= 95;
    }

    public function getPerformanceBadge()
    {
        $achievement = $this->achievement_percentage;
        if ($achievement >= 95) return 'success';
        if ($achievement >= 90) return 'info';
        if ($achievement >= 80) return 'warning';
        return 'danger';
    }
}