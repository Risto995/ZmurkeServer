<?php

use Illuminate\Database\Seeder;

class FriendsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i<=100; $i++) {
            for ($j = 1; $j < $i; $j++) {
                DB::table('friends')->insert([
                    'first_user' => $i,
                    'second_user' => $j
                ]);

                DB::table('friends')->insert([
                    'first_user' => $j,
                    'second_user' => $i
                ]);
            }
        }
    }
}
