<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // سؤال 1: True/False
        DB::table('options')->insert([
            ['question_id' => 1, 'text' => 'True',  'is_correct' => true],
            ['question_id' => 1, 'text' => 'False', 'is_correct' => false],
        ]);

        // سؤال 2: Multiple Choice
        DB::table('options')->insert([
            ['question_id' => 2, 'text' => 'goes',  'is_correct' => true],
            ['question_id' => 2, 'text' => 'go',    'is_correct' => false],
            ['question_id' => 2, 'text' => 'went',  'is_correct' => false],
            ['question_id' => 2, 'text' => 'going', 'is_correct' => false],
        ]);

    }
}
