<?php

namespace Database\Seeders;

use App\Models\accounts;
use App\Models\attendants;
use Illuminate\Database\Seeder;

class accountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        accounts::create(
            [
                'title' => 'Cash Account',
                'type' => 'Business',
                'category' => 'Cash',
            ]
        );

        accounts::create(
            [
                'title' => 'Daily Sale',
                'type' => 'Customer',

            ]
        );

        accounts::create(
            [
                'title' => 'Walk-In Supplier',
                'type' => 'Supplier',
            ]
        );

        attendants::create([
            'name' => 'Test Attendant',
            'phone' => '03001234567',
            'address' => 'Test Address',
            'is_active' => 1,
        ]);
    }
}
