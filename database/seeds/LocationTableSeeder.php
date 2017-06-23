<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for($i = 1; $i<=50; $i++) {
         for($j = 1; $j <= 19; $j++){
             DB::table('locations')->insert([
                'user_id' => $i,
                 'latitude' => $faker->latitude($min = -90, $max = 90),
                 'longitude' => $faker->longitude($min = -180, $max = 180),
                 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                 'active' => false
             ]);
           }

            $id = DB::table('locations')->insertGetId([
                'user_id' => $i,
                'latitude' => $faker->latitude($min = -90, $max = 90),
                'longitude' => $faker->longitude($min = -180, $max = 180),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'active' => true
            ]);

            DB::table('users')
                ->where('id', $i)
                ->update(['current_location' => $id]);
        }
    }
}
