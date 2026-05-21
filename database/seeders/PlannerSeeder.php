<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TbPlanner;
use App\Models\User;

class PlannerSeeder extends Seeder
{
    public function run(): void
    {
        $plannerUser1 = User::where('email', 'planner@pama.com')->first();
        $plannerUser2 = User::where('email', 'planner2@pama.com')->first();

        TbPlanner::create([
            'nama' => 'Planner PAMA',
            'email' => 'planner@pama.com',
            'telepon' => '081234567893',
            'perusahaan' => 'PAMA ARIA',
            'jabatan' => 'Part Planner',
            'department' => 'Component & Part',
            'status' => 'active',
            'user_id' => $plannerUser1?->id,
        ]);

        TbPlanner::create([
            'nama' => 'Planner Dua',
            'email' => 'planner2@pama.com',
            'telepon' => '081234567894',
            'perusahaan' => 'PAMA ARIA',
            'jabatan' => 'Senior Planner',
            'department' => 'Maintenance Planning',
            'status' => 'active',
            'user_id' => $plannerUser2?->id,
        ]);
    }
}