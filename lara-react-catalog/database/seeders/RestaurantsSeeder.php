<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantsSeeder extends Seeder
{
    public function run()
    {
        $restaurants = [
            [
                'id' => 101,
                'name' => 'Tandoori Treats',
                'location' => 'Bangalore',
                'cuisine' => 'North Indian',
            ],
            [
                'id' => 102,
                'name' => 'Sushi Bay',
                'location' => 'Mumbai',
                'cuisine' => 'Japanese',
            ],
            [
                'id' => 103,
                'name' => 'Pasta Palace',
                'location' => 'Delhi',
                'cuisine' => 'Italian',
            ],
            [
                'id' => 104,
                'name' => 'Burger Hub',
                'location' => 'Hyderabad',
                'cuisine' => 'American',
            ],
        ];
        foreach ($restaurants as $restaurant) {
            Restaurant::updateOrCreate(['id' => $restaurant['id']], $restaurant);
        }
    }
}
