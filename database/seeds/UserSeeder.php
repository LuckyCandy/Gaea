<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = \Illuminate\Support\Carbon::now();
        \Illuminate\Support\Facades\DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gaea.com',
            'is_admin' => 1,
            'password' => bcrypt('123456'),
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
}
