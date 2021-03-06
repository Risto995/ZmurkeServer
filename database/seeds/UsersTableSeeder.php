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
                'phone_number' => $faker->phoneNumber,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'api_token' => str_random(60),
                'avatar' => $faker->imageUrl(100, 100, 'cats'),
                'password' => $faker->password,
                'points' => 0,
                'current_location' => null,
                'current_game' => null,
                'color' => $faker->hexColor(),
            ]);
        }
    }
}
