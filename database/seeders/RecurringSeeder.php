<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecurringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('recurrings')->insert([
            'name' => "Recurring 1",
            'start_date' => "2022-03-16",
            'end_date' => "2023-03-16",
            'duration' => "2 weeks",
        ]);
        DB::table('recurrings')->insert([
            'name' => "Recurring 2",
            'start_date' => "2022-02-10",
            'end_date' => "2022-08-10",
            'duration' => "1 months",
        ]);
    }
}
