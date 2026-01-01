<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttemptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('attempts')->insert([
            // ليان تحاول السؤال 1
            ['student_id' => 1, 'question_id' => 1, 'is_correct' => true,  'tries' => 1],
            // فرح تحاول السؤال 2
            ['student_id' => 2, 'question_id' => 2, 'is_correct' => true,  'tries' => 1],
        ]);

    }
}
