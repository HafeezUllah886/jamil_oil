<?php

namespace Database\Seeders;

use App\Models\products;
use Illuminate\Database\Seeder;

class productsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Petrol', 'price' => 375, 'unit' => 'Liter'],
            ['name' => 'Diesel', 'price' => 385, 'unit' => 'Liter'],
            ['name' => '20w50 Engine Oil', 'price' => 300, 'unit' => 'Piece'],
        ];
        products::insert($data);
    }
}
