<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InspectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * サンプル検査員データ
         */
        \App\Models\Inspector::insert([
            [
                'name' => '田中 一郎',
                'role' => '主任検査員',
                'email' => 'tanaka@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '佐藤 花子',
                'role' => '検査員',
                'email' => 'sato@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '鈴木 次郎',
                'role' => '品質管理',
                'email' => 'suzuki@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
