<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->insert([
            'name' => 'General Account',
            'email' => 'email@test.com',
            'password' => bcrypt('secret'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('wallets')->insert([
            'balance' => 0,
            'customer_id' => 1,
            'currency_id' => 'MXN',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
