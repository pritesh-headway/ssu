<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 200; $i++) {
            \DB::table('banners')->insert([
                'banner_name' => $faker->sentence(
                    $nbWords = 6,
                    $variableNbWords = true
                ),
                'image' => $faker->text($maxNbChars = 500),
            ]);
        }
    }
}
