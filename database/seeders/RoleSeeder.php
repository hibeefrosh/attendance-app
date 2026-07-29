<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::query()->upsert([
            ['name' => 'Lecturer', 'slug' => Role::LECTURER, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Student', 'slug' => Role::STUDENT, 'created_at' => now(), 'updated_at' => now()],
        ], ['slug'], ['name', 'updated_at']);
    }
}
