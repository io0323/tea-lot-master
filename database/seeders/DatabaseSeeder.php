<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // ロール作成
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleInspector = Role::firstOrCreate(['name' => 'inspector']);
        $roleViewer = Role::firstOrCreate(['name' => 'viewer']);

        // 管理者ユーザーにadminロールを付与
        $admin->assignRole($roleAdmin);

        // サンプルデータ投入の順序を保証
        $this->call([
            SupplierSeeder::class,
            InspectorSeeder::class,
            TeaLotSeeder::class,
        ]);
    }
}
