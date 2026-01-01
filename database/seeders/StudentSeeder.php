<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('students')->insert([
            ['name' => 'Layan',   'grade_id' => 1, 'points' => 0],
            ['name' => 'Farah',   'grade_id' => 1, 'points' => 10],
            ['name' => 'Rahar',    'grade_id' => 2, 'points' => 5],
            ['name' => 'Lojein',    'grade_id' => 2, 'points' => 0],
        ]);

    }
}
