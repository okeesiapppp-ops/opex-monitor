<?php

namespace App\Exports;

use App\Models\TbBacklogDaily;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BacklogDailyExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private $tanggal;
    private $no = 0;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        return TbBacklogDaily::with(['vendor', 'planner'])
            ->whereDate('tanggal_bcs', $this->tanggal)
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Unit Number',
            'Model',
            'Part Number',
            'Part Name',
            'Qty',
            'Status',
            'Location',
            'ETA',
            'Stock Position',
            'Vendor',
            'Planner',
            'Is Ready',
            'Keterangan',
        ];
    }

    public function map($row): array
    {
        $this->no++;

        return [
            $this->no,
            $row->tanggal_bcs ? $row->tanggal_bcs->format('d/m/Y') : '-',
            $row->unit_number,
            $row->model,
            $row->part_number,
            $row->part_name,
            $row->qty,
            $row->status,
            $row->location ?? '-',
            $row->eta ? $row->eta->format('d/m/Y') : '-',
            $row->stock_position ?? '-',
            $row->vendor ? $row->vendor->name : '-',
            $row->planner ? $row->planner->name : '-',
            $row->is_ready ? 'Ready' : 'Pending',
            $row->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ],
        ];
    }
}