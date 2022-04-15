<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transactions')->insert([
            'title' => "title of transaction",
            'description' => "description of fixed transaction - expenses",
            'category_id' => 1,
            'amount' => 700.00,
            'currency'   =>  "USD",
            'date' => "2017-06-15"
        ]);
        DB::table('transactions')->insert([
            'title' => "title of transaction",
            'description' => "description of recurring transaction - expenses",
            'category_id' => 1,
            'amount' => 700.00,
            'currency'   =>  "USD",
            'date' => "2010-06-30",
            'recurring_id' => 1
        ]);
        DB::table('transactions')->insert([
            'title' => "title of transaction",
            'description' => "description of fixed transaction - income",
            'category_id' => 3,
            'amount' => 700.00,
            'currency'   =>  "USD",
            'date' => "2021-06-15"
        ]);
        DB::table('transactions')->insert([
            'title' => "title of transaction",
            'description' => "description of recurring transaction - income",
            'category_id' => 3,
            'amount' => 700.00,
            'currency'   =>  "USD",
            'date' => "2020-06-30",
            'recurring_id' => 1
        ]);
    }
}
