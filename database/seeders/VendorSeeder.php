<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TbVendor;
use App\Models\User;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendorUser1 = User::where('email', 'vendor@uta.com')->first();
        $vendorUser2 = User::where('email', 'vendor2@uta.com')->first();

        TbVendor::create([
            'nama_vendor' => 'UT Part Jakarta',
            'pic' => 'Budi Santoso',
            'telepon' => '02112345678',
            'email' => 'vendor@uta.com',
            'alamat' => 'Jl. Raya Bekasi No. 1, Jakarta Timur',
            'kode_vendor' => 'VND-001',
            'status' => 'active',
            'user_id' => $vendorUser1?->id,
        ]);

        TbVendor::create([
            'nama_vendor' => 'UT Part Surabaya',
            'pic' => 'Siti Rahayu',
            'telepon' => '03187654321',
            'email' => 'vendor2@uta.com',
            'alamat' => 'Jl. Industri No. 5, Surabaya',
            'kode_vendor' => 'VND-002',
            'status' => 'active',
            'user_id' => $vendorUser2?->id,
        ]);

        TbVendor::create([
            'nama_vendor' => 'UT Part Balikpapan',
            'pic' => 'Ahmad Fauzi',
            'telepon' => '05421234567',
            'email' => 'vendor3@uta.com',
            'alamat' => 'Jl. Soekarno Hatta No. 10, Balikpapan',
            'kode_vendor' => 'VND-003',
            'status' => 'active',
            'user_id' => null,
        ]);
    }
}