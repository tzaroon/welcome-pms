<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            DB::table('users')->insert([
                'id' => 1,
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'company_id' => 1,
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'phone_number' => '+917006867241'
            ]);
        } catch(\Exception $e) {

        }
    }
}
