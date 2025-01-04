<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            'JSS1',
            'JSS2',
            'JSS3',
            'SS1 (Science)',
            'SS1 (Arts)',
            'SS1 (Commerce)',
            'SS2 (Science)',
            'SS2 (Arts)',
            'SS2 (Commerce)',
            'SS3 (Science)',
            'SS3 (Arts)',
            'SS3 (Commerce)',
        ];        

        foreach ($grades as $gradeName) {
            Grade::firstOrCreate(
                ['name' => $gradeName],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
