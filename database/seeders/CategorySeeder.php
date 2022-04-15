<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name' => "Food",
            'type' => "expense",
        ]);

        DB::table('categories')->insert([
            'name' => "Transportation",
            'type' => "expense",
        ]);
        DB::table('categories')->insert([
            'name' => "Funding",
            'type' => "income",
        ]);
        DB::table('categories')->insert([
            'name' => "Projects",
            'type' => "income",
        ]);
    }
}
