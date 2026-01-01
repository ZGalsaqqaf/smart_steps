<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('questions')->insert([
            [
                'text'      => 'The sun rises in the east.',
                'type'      => 'true_false',
                'category'  => 'General Knowledge',
                'grade_id'  => 1,
            ],
            [
                'text'      => 'Choose the correct verb: Ali ____ to school every day.',
                'type'      => 'multiple_choice',
                'category'  => 'Present Simple',
                'grade_id'  => 1,
            ],
        ]);

    }
}
