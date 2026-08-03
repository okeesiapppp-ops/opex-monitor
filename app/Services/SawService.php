<?php

namespace App\Services;

use App\Models\TbBacklogDaily;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class SawService
{
    protected array $weights = [
        'C1' => 0.4, 'C2' => 0.3, 'C3' => 0.2, 'C4' => 0.1,
    ];

    protected array $types = [
        'C1' => 'benefit', 'C2' => 'benefit', 'C3' => 'cost', 'C4' => 'benefit',
    ];

    protected array $unitUrgensiMap = [
        'DT' => 3, 'EX' => 3, 'DZ' => 2, 'WL' => 2, 'LD' => 1,
    ];

    public function recalculateOutstanding(): Collection
    {
        // Pola sama seperti BacklogDailyController@index: semua kecuali 'cancel'
        $backlogs = TbBacklogDaily::where('status', 'outstanding')->get();
        if ($backlogs->isEmpty()) return collect();

        $matrix = $backlogs->map(function ($item) {
            return [
                'id' => $item->id,
                'C1' => (float) $this->resolveUrgensi($item->unit),
                'C2' => (float) ($item->qty_zboh ?? 0),
                'C3' => (float) $this->hitungJarakEksekusi($item->plan_date),
                'C4' => (float) ($item->qty ?? 0),
            ];
        });

        $normalized = $this->normalize($matrix);
        $scored = $normalized->map(function ($row) {
            $vi = 0;
            foreach ($this->weights as $k => $b) $vi += $b * $row[$k . '_norm'];
            $row['Vi'] = round($vi, 4);
            return $row;
        });

        $ranked = $scored->sortByDesc('Vi')->values();
        foreach ($ranked as $index => $row) {
            TbBacklogDaily::where('id', $row['id'])->update([
                'priority_score' => $row['Vi'],
                'priority_rank'  => $index + 1,
            ]);
        }
        return $ranked;
    }

    protected function resolveUrgensi(?string $unitCode): int
    {
        if (!$unitCode) return 1;
        $prefix = strtoupper(substr(preg_replace('/[0-9]/', '', $unitCode), 0, 2));
        return $this->unitUrgensiMap[$prefix] ?? 1;
    }

    /**
     * Sama persis dengan logic di BacklogDailyController@index,
     * supaya nilai C3 konsisten dengan yang tampil di tabel.
     */
    protected function hitungJarakEksekusi($planDate): int
    {
        if (!$planDate) return 0;
        $planDate = Carbon::parse($planDate);
        $today = Carbon::today();
        return (int) $planDate->diffInDays($today, false);
    }

    protected function normalize(Collection $matrix): Collection
    {
        $maxValues = [];
        foreach (['C1','C2','C3','C4'] as $k) $maxValues[$k] = $matrix->pluck($k)->max();
        $minC3 = $matrix->pluck('C3')->min();

        return $matrix->map(function ($row) use ($maxValues, $minC3) {
            foreach (['C1','C2','C3','C4'] as $k) {
                $x = $row[$k];
                if ($this->types[$k] === 'benefit') {
                    $max = $maxValues[$k];
                    $row[$k . '_norm'] = $max > 0 ? $x / $max : 0;
                } else {
                    $shifted = $x - $minC3 + 1;
                    $row[$k . '_norm'] = $shifted > 0 ? 1 / $shifted : 0;
                }
            }
            return $row;
        });
    }
}
