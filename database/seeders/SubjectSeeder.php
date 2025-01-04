<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            // JSS Levels
            'English Language',
            'Basic Mathematics',
            'Basic Science',
            'Social Studies',
            'Civic Education',
            'Computer Studies',
            'Agricultural Science',
            'Home Economics',
            'Physical and Health Education',
            'Religious Studies',
            'French',
            'Basic Technology', // For JSS2 and JSS3
        
            // SS Levels (Science, Arts, Commerce)
            'Intermediate Mathematics',
            'Biology',
            'Chemistry',
            'Physics',
            'Further Mathematics',
            'Geography',
            'Literature in English',
            'Government',
            'History',
            'Economics',
            'Accounting',
            'Commerce',
            'Business Studies',
        ];
        

        foreach ($subjects as $subjectName) {
            Subject::firstOrCreate(
                ['name' => $subjectName],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
