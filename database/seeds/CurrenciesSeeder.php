<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            'id' => 'MXN',
            'name' => 'Pesos Mexicanos',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
