<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch the Role ID where the name is like 'administrator'
        $administratorRole = Role::where('name', 'like', 'Administrator')->first();

        if ($administratorRole) {
            User::firstOrCreate(
                ['email' => 'admin01@email.com'], // Check if user exists by email
                [
                    'name' => 'Admin User',
                    'role_id' => $administratorRole->id,
                    'password' => Hash::make('12345678'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        } else {
            $this->command->error('Administrator role not found. Please run the RoleSeeder first.');
        }
    }
}
