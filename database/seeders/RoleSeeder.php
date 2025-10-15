<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            ['name' => 'admin',  'label' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manager',  'label' => 'Quản lý', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'editor', 'label' => 'Biên tập viên', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'user',   'label' => 'Người dùng',    'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
