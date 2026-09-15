<?php

namespace Database\Seeders;

use App\Models\expenseCategories;
use Illuminate\Database\Seeder;

class ExpenseCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Maintenance'],
            ['name' => 'Staff Salary'],
            ['name' => 'Utility'],
        ];
        expenseCategories::insert($data);
    }
}
