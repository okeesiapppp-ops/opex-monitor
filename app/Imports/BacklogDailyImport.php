<?php

namespace App\Imports;

use App\Models\TbBacklogDaily;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Collection;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\WithMultipleSheets as WMS;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BacklogDailyImport implements WithMultipleSheets
{
    private $tanggal;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function sheets(): array
    {
        $sheets = [];
        $filePath = request()->file('file')->getPathname();
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        foreach ($spreadsheet->getSheetNames() as $name) {
            $sheets[$name] = new BCSSheetImport($this->tanggal);
        }
        return $sheets;
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
        $found = null;
        foreach ($data as $k => $v) {
            $normalized_k = strtolower(trim((string)$k));
            foreach ($keys as $key) {
                if ($normalized_k === strtolower(trim((string)$key))) {
                    if ($v !== null && $v !== '') {
                        $found = $v;
                    }
                }
            }
        }
        return $found !== null ? $found : $default;
    }

    public function collection(Collection $rows)
    {
        $headers = null;

        foreach ($rows as $index => $row) {
            if ($row->filter()->isEmpty()) continue;

            if ($headers === null) {
                $rowArr = $row->toArray();
                $rowLower = array_map('strtolower', array_map('trim', array_map('strval', $rowArr)));
                if (in_array('reservation', $rowLower)) {
                    $headers = $rowArr;
                }
                continue;
            }



            $data = array_combine($headers, $row->toArray());
            \Log::error('ROW rsv='.($data['Reservation'] ?? $data['reservation'] ?? 'null'));

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
            $etaRaw = $this->get($data, ['ETA', 'eta', 'Eta', 'ETA ONSITE', 'eta onsite', 'ETA ONSI', 'eta onsi']);
            $eta = null;
            if (!empty($etaRaw) && $etaRaw != 0) {
                try {
                    if (is_numeric($etaRaw) && $etaRaw > 1000) {
                        $eta = \Carbon\Carbon::createFromTimestamp(($etaRaw - 25569) * 86400)->toDateString();
                    } elseif (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $etaRaw, $m)) {
                        $eta = \Carbon\Carbon::createFromDate($m[3], $m[2], $m[1])->toDateString();
                    } else {
                        $eta = \Carbon\Carbon::parse($etaRaw)->toDateString();
                    }
                } catch (\Exception $e) { $eta = null; }
            }
            $posisi            = $this->get($data, ['Posisi', 'posisi', 'POSISI', 'Stock Position', 'stock position', 'STOCK POSITION', 'Location', 'location']);
            $prepareBy         = $this->get($data, ['Prepare by', 'prepare by', 'PREPARE BY', 'Prepared by', 'prepared by']);

            // Filter: hanya import data dengan Prepare by = UT
            if (!empty($prepareBy) && strtoupper(trim($prepareBy)) !== 'UT') {
                \Log::error('SKIP_PREPARE rsv='.$reservationNumber.' prepare='.json_encode($prepareBy));
                continue;
            }

            if (empty($reservationNumber) && empty($equipment)) {
                \Log::error('SKIP_EMPTY rsv='.$reservationNumber);
                continue;
            }

            // Parse plan_date
            $planDateParsed = null;
            if (!empty($planDate)) {
                try {
                    if (is_numeric($planDate)) {
                        $planDateParsed = Carbon::createFromTimestamp(($planDate - 25569) * 86400)->toDateString();
                    } elseif (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $planDate, $m)) {
                        $planDateParsed = Carbon::createFromDate($m[3], $m[2], $m[1])->toDateString();
                    } else {
                        $planDateParsed = Carbon::parse($planDate)->toDateString();
                    }
                } catch (\Exception $e) {
                    $planDateParsed = null;
                }
            }

            // Parse tanggal_bcs
            $tanggalBcsParsed = null;
            if (!empty($tanggalBcs)) {
                try {
                    if (is_numeric($tanggalBcs)) {
                        $tanggalBcsParsed = Carbon::createFromTimestamp(($tanggalBcs - 25569) * 86400)->toDateString();
                    } elseif (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $tanggalBcs, $m)) {
                        $tanggalBcsParsed = Carbon::createFromDate($m[3], $m[2], $m[1])->toDateString();
                    } else {
                        $tanggalBcsParsed = Carbon::parse($tanggalBcs)->toDateString();
                    }
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
                    'eta'            => $eta ?? null,
                    'posisi'         => $posisi ?? null,
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
                    'eta'                => $eta ?? null,
                    'posisi'             => $posisi ?? null,
                    'tanggal'            => $this->tanggal,
                    'status'             => 'outstanding',
                    'is_ready'           => false,
                ]);
            }
        }
    }
}