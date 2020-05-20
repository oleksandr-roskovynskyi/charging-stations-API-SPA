<?php

use Illuminate\Database\Seeder;
use App\Models\ChargingStation;

class ChargingStationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChargingStation::truncate();

        $dateTimeNow = \Carbon\Carbon::now();

        $arrayChargingStations = [];

        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Faker\Provider\uk_UA\Address($faker));
        $faker->addProvider(new \Faker\Provider\uk_UA\Company($faker));

        while (true) {
            $uniqueName = $faker->companyPrefix . ' ' . $faker->companyName . '-' . $faker->companySuffix; //max ~300

            if (isset($arrayChargingStations[$uniqueName])) {
                continue;
            }

            $arrayChargingStations[$uniqueName] = [
                'name' => $uniqueName,
                'city' => $faker->city,  //24
                'open_from' => $faker->time($format = 'H:i'),
                'open_to' => $faker->time($format = 'H:i'),
                'latitude' => $faker->latitude($min = 44, $max = 50),
                'longitude' => $faker->longitude($min = 22, $max = 39),
                'created_at' => $dateTimeNow,
                'updated_at' => $dateTimeNow,
            ];

            if (\count($arrayChargingStations) >= 300) {
                break;
            }
        }

        ChargingStation::insert(array_values($arrayChargingStations));
    }
}
