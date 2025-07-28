<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeaLotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * サンプル茶ロットデータ
         */
        \App\Models\TeaLot::insert([
            [
                'batch_code' => 'SJK20240724A',
                'tea_type' => '煎茶',
                'origin' => '静岡県',
                'moisture' => 4.2,
                'aroma_score' => 85,
                'color_score' => 90,
                'inspected_at' => now()->subDays(2),
                'supplier_id' => 1,
                'inspector_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_code' => 'UJI20240723B',
                'tea_type' => '玉露',
                'origin' => '京都府宇治市',
                'moisture' => 3.8,
                'aroma_score' => 92,
                'color_score' => 88,
                'inspected_at' => now()->subDays(1),
                'supplier_id' => 2,
                'inspector_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_code' => 'YAME20240722C',
                'tea_type' => '玉緑茶',
                'origin' => '福岡県八女市',
                'moisture' => 5.1,
                'aroma_score' => 80,
                'color_score' => 85,
                'inspected_at' => now()->subDays(3),
                'supplier_id' => 3,
                'inspector_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_code' => 'KGS20240721D',
                'tea_type' => '知覧茶',
                'origin' => '鹿児島県南九州市',
                'moisture' => 4.8,
                'aroma_score' => 78,
                'color_score' => 82,
                'inspected_at' => now()->subDays(4),
                'supplier_id' => 1,
                'inspector_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_code' => 'MZK20240720E',
                'tea_type' => '釜炒り茶',
                'origin' => '宮崎県五ヶ瀬町',
                'moisture' => 3.5,
                'aroma_score' => 88,
                'color_score' => 91,
                'inspected_at' => now()->subDays(5),
                'supplier_id' => 2,
                'inspector_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_code' => 'SJK20240719F',
                'tea_type' => '抹茶',
                'origin' => '静岡県',
                'moisture' => 4.0,
                'aroma_score' => 90,
                'color_score' => 95,
                'inspected_at' => now()->subDays(6),
                'supplier_id' => 1,
                'inspector_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_code' => 'UJI20240718G',
                'tea_type' => '碾茶',
                'origin' => '京都府宇治市',
                'moisture' => 3.2,
                'aroma_score' => 95,
                'color_score' => 89,
                'inspected_at' => now()->subDays(7),
                'supplier_id' => 2,
                'inspector_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_code' => 'YAME20240717H',
                'tea_type' => 'ほうじ茶',
                'origin' => '福岡県八女市',
                'moisture' => 5.5,
                'aroma_score' => 70,
                'color_score' => 80,
                'inspected_at' => now()->subDays(8),
                'supplier_id' => 3,
                'inspector_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_code' => 'KGS20240716I',
                'tea_type' => '番茶',
                'origin' => '鹿児島県霧島市',
                'moisture' => 4.6,
                'aroma_score' => 75,
                'color_score' => 77,
                'inspected_at' => now()->subDays(9),
                'supplier_id' => 1,
                'inspector_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_code' => 'MZK20240715J',
                'tea_type' => '釜炒り茶',
                'origin' => '宮崎県都城市',
                'moisture' => 3.9,
                'aroma_score' => 85,
                'color_score' => 93,
                'inspected_at' => now()->subDays(10),
                'supplier_id' => 2,
                'inspector_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
