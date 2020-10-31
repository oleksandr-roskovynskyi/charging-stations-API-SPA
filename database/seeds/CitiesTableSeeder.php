<?php

use App\Models\City;
use Carbon\Carbon;
use Faker\Factory;
use Faker\Provider\uk_UA\Address;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::truncate();

        $dateTimeNow = Carbon::now();

        $arrayCities = [];

        $faker = Factory::create();
        $faker->addProvider(new Address($faker));

        while (true) {
            $city = $faker->city; //max 24

            if (isset($arrayCities[$city])) {
                continue;
            }

            $arrayCities[$city] = [
                'city' => $city, //24
                'country_id' => 1,
                'created_at' => $dateTimeNow,
                'updated_at' => $dateTimeNow,
            ];

            if (\count($arrayCities) >= 24) {
                break;
            }
        }

        City::insert(array_values($arrayCities));
    }
}
