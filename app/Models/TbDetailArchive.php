<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbDetailArchive extends Model
{
    use HasFactory;

    protected $table = 'tb_detail_archive';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'archive_id',
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
        'status',
        'tanggal',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'plan_date'   => 'date',
        'tanggal_bcs' => 'date',
        'qty'         => 'integer',
    ];

    public function archive()
    {
        return $this->belongsTo(TbArchive::class, 'archive_id', 'id');
    }
}