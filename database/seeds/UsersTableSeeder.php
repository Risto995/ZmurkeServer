<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Faker::create();
        for($i = 0; $i<50; $i++) {
            DB::table('users')->insert([
                'name' => $faker->firstName,
                'email' => $faker->freeEmail,
                'date_of_birth' => $faker->dateTimeThisCentury->format('Y-m-d'),
                'avatar' => $faker->imageUrl(100, 100, 'cats'),
                'password' => $faker->password,
                'points' => 0,
                'current_location' => null,
                'current_game' => null,
            ]);
        }
    }
}
