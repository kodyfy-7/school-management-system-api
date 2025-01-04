<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Administrator',
            'Teacher',
            'Student',
            'Guardian',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
