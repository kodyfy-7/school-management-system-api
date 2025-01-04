<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Multiple choice',
            'Essay',
        ];

        foreach ($types as $typeName) {
            Type::firstOrCreate(
                ['name' => $typeName],
                [
                    'slug' => \Str::slug($typeName),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
