<?php

namespace App\Imports;

use App\Models\TbBacklogDaily;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class BacklogDailyImport implements WithMultipleSheets
{
    private $tanggal;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function sheets(): array
    {
        return [
            'BCS' => new BCSSheetImport($this->tanggal),
        ];
    }
}

class BCSSheetImport implements ToCollection
{
    private $tanggal;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    private function get(array $data, array $keys, $default = null)
    {
        $lookup = [];
        foreach ($data as $k => $v) {
            $lookup[strtolower(trim((string)$k))] = $k;
        }
        foreach ($keys as $key) {
            $normalized = strtolower(trim((string)$key));
            if (isset($lookup[$normalized])) {
                $val = $data[$lookup[$normalized]];
                if ($val !== null && $val !== '') {
                    return $val;
                }
            }
        }
        return $default;
    }

    public function collection(Collection $rows)
    {
        $headers = null;

        foreach ($rows as $index => $row) {
            if ($row->filter()->isEmpty()) continue;

            if ($index === 1) {
                $headers = $row->toArray();
                continue;
            }

            if ($headers === null || $index < 2) continue;

            $data = array_combine($headers, $row->toArray());

            $reservationNumber = $this->get($data, ['Reservation', 'reservation', 'RESERVATION']);
            $reservationItem   = $this->get($data, ['Rsv Item', 'rsv item', 'RSV ITEM', 'Rsv_Item']);
            $moDescription     = $this->get($data, ['backlog desc', 'Backlog Desc', 'BACKLOG DESC', 'backlog_desc']);
            $equipment         = $this->get($data, ['Equipment', 'equipment', 'EQUIPMENT']);
            $egi               = $this->get($data, ['EGI', 'egi', 'Egi']);
            $partNumber        = $this->get($data, ['PN', 'pn', 'Pn', 'Part Number', 'part number', 'PART NUMBER']);
            $npn               = $this->get($data, ['NPN', 'npn', 'Npn']);
            $manufacture       = $this->get($data, ['Mnemonic Requested', 'mnemonic requested', 'Mnemonic', 'mnemonic', 'MNEMONIC']);
            $qty               = $this->get($data, ['qty', 'QTY', 'Qty', 'QTY REQ', 'qty req'], 1);
            $unit              = $this->get($data, ['Unit', 'unit', 'UNIT']);
            $planDate          = $this->get($data, ['plan date', 'Plan Date', 'PLAN DATE', 'plan_date']);
            $jarakEksekusi     = $this->get($data, ['Jarak Ekeskusi', 'Jarak Eksekusi', 'jarak eksekusi', 'JARAK EKSEKUSI']);
            $tanggalBcs        = $this->get($data, ['TANGGAL BCS', 'tanggal bcs', 'Tanggal BCS', 'tanggal_bcs']);
            $remarks           = $this->get($data, ['REMARKS 1', 'remarks 1', 'Remarks 1', 'REMARKS', 'remarks', 'Remarks']);

            if (empty($reservationNumber) && empty($equipment)) continue;

            // Parse plan_date
            $planDateParsed = null;
            if (!empty($planDate)) {
                try {
                    $planDateParsed = is_numeric($planDate)
                        ? Carbon::createFromTimestamp(($planDate - 25569) * 86400)->toDateString()
                        : Carbon::parse($planDate)->toDateString();
                } catch (\Exception $e) {
                    $planDateParsed = null;
                }
            }

            // Parse tanggal_bcs
            $tanggalBcsParsed = null;
            if (!empty($tanggalBcs)) {
                try {
                    $tanggalBcsParsed = is_numeric($tanggalBcs)
                        ? Carbon::createFromTimestamp(($tanggalBcs - 25569) * 86400)->toDateString()
                        : Carbon::parse($tanggalBcs)->toDateString();
                } catch (\Exception $e) {
                    $tanggalBcsParsed = null;
                }
            }

            // =====================================================
            // CEK DUPLIKAT: reservation + item + tanggal_bcs
            // → Data Feb (bcs Feb) dan Mar (bcs Mar) TIDAK saling timpa
            // → Upload file yang sama 2x = update, tidak dobel
            // =====================================================
            $existing = TbBacklogDaily::where('reservation_number', $reservationNumber)
                ->where('reservation_item', $reservationItem)
                ->where('tanggal_bcs', $tanggalBcsParsed)
                ->first();

            if ($existing) {
                // UPDATE — data sudah ada dengan tanggal_bcs yang sama
                // Hanya update info teknis, TIDAK timpa tanggal upload & tanggal_bcs
                $existing->update([
                    'mo_description' => $moDescription,
                    'equipment'      => $equipment,
                    'egi'            => $egi,
                    'part_number'    => $partNumber,
                    'npn'            => $npn,
                    'manufacture'    => $manufacture,
                    'qty'            => is_numeric($qty) ? (int)$qty : 1,
                    'unit'           => $unit,
                    'plan_date'      => $planDateParsed,
                    'jarak_eksekusi' => $jarakEksekusi,
                    'remarks'        => $remarks,
                    // TIDAK update: tanggal_bcs, tanggal (upload), status
                ]);
            } else {
                // INSERT BARU — data bulan berbeda masuk sebagai record baru
                TbBacklogDaily::create([
                    'reservation_number' => $reservationNumber,
                    'reservation_item'   => $reservationItem,
                    'mo_description'     => $moDescription,
                    'equipment'          => $equipment,
                    'egi'                => $egi,
                    'part_number'        => $partNumber,
                    'npn'                => $npn,
                    'manufacture'        => $manufacture,
                    'qty'                => is_numeric($qty) ? (int)$qty : 1,
                    'unit'               => $unit,
                    'plan_date'          => $planDateParsed,
                    'jarak_eksekusi'     => $jarakEksekusi,
                    'tanggal_bcs'        => $tanggalBcsParsed,
                    'remarks'            => $remarks,
                    'tanggal'            => $this->tanggal,
                    'status'             => 'pending',
                    'is_ready'           => false,
                ]);
            }
        }
    }
}