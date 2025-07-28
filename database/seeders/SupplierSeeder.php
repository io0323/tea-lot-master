<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * サンプルサプライヤーデータ
         */
        \App\Models\Supplier::insert([
            [
                'name' => '静岡茶園',
                'location' => '静岡県',
                'contact' => '054-123-4567',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '宇治製茶',
                'location' => '京都府宇治市',
                'contact' => '0774-12-3456',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '八女茶ファーム',
                'location' => '福岡県八女市',
                'contact' => '0943-22-1111',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
