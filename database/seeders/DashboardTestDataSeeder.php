<?php

namespace Database\Seeders;

use App\Models\accounts;
use App\Models\expenseCategories;
use App\Models\expenses;
use App\Models\products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\salePayments;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DashboardTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure essential accounts exist
        $businessAccount = accounts::where('type', 'Business')->first();
        if (!$businessAccount) {
            $businessAccount = accounts::create([
                'title' => 'Cash Account',
                'type' => 'Business',
                'category' => 'Cash',
            ]);
        }

        $customerAccount = accounts::where('type', 'Customer')->first();
        if (!$customerAccount) {
            $customerAccount = accounts::create([
                'title' => 'Daily Sale',
                'type' => 'Customer',
            ]);
        }

        $supplierAccount = accounts::where('type', 'Supplier')->first();
        if (!$supplierAccount) {
            $supplierAccount = accounts::create([
                'title' => 'Walk-In Supplier',
                'type' => 'Supplier',
            ]);
        }

        // 2. Ensure products exist with correct prices
        $petrol = products::where('name', 'Petrol')->first();
        if (!$petrol) {
            $petrol = products::create(['name' => 'Petrol', 'price' => 375, 'unit' => 'Liter', 'is_active' => 1]);
        }
        $diesel = products::where('name', 'Diesel')->first();
        if (!$diesel) {
            $diesel = products::create(['name' => 'Diesel', 'price' => 385, 'unit' => 'Liter', 'is_active' => 1]);
        }
        $oil = products::where('name', '20w50 Engine Oil')->first();
        if (!$oil) {
            $oil = products::create(['name' => '20w50 Engine Oil', 'price' => 300, 'unit' => 'Piece', 'is_active' => 1]);
        }

        // 3. Create expense categories
        $categoriesData = ['Electricity', 'Salaries', 'Rent', 'Office Supplies', 'Maintenance'];
        $categories = [];
        foreach ($categoriesData as $catName) {
            $categories[] = expenseCategories::firstOrCreate(['name' => $catName]);
        }

        // 4. Clean up existing transaction-related data to avoid duplicate records bloating the DB during seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('transactions')->truncate();
        DB::table('stocks')->truncate();
        DB::table('sales')->truncate();
        DB::table('sale_details')->truncate();
        DB::table('sale_payments')->truncate();
        DB::table('purchases')->truncate();
        DB::table('purchase_details')->truncate();
        DB::table('expenses')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 5. Generate mock data for the last 6 months
        $now = Carbon::now();
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = (clone $now)->subMonths($i);
            $daysInMonth = $monthDate->daysInMonth;

            // Generate Purchases (1 per month)
            $purchaseDate = (clone $monthDate)->day(rand(2, 5))->format('Y-m-d');
            $refPurchase = getRef();
            $purchaseTotal = 0;

            $purchaseItems = [
                ['product' => $petrol, 'qty' => rand(6000, 10000), 'cost' => 350],
                ['product' => $diesel, 'qty' => rand(5000, 8000), 'cost' => 360],
                ['product' => $oil, 'qty' => rand(40, 80), 'cost' => 250],
            ];

            $purchaseRecord = purchase::create([
                'supplier_id' => $supplierAccount->id,
                'date' => $purchaseDate,
                'notes' => "Monthly stock purchase for " . $monthDate->format('F Y'),
                'inv' => 'INV-' . $monthDate->format('Ym') . '-01',
                'refID' => $refPurchase,
            ]);

            foreach ($purchaseItems as $item) {
                $amount = $item['qty'] * $item['cost'];
                $purchaseTotal += $amount;

                purchase_details::create([
                    'purchase_id' => $purchaseRecord->id,
                    'product_id' => $item['product']->id,
                    'price' => $item['cost'],
                    'qty' => $item['qty'],
                    'amount' => $amount,
                    'date' => $purchaseDate,
                    'refID' => $refPurchase,
                ]);

                createStock($item['product']->id, $item['qty'], 0, $purchaseDate, "Purchased in " . $purchaseRecord->id, $refPurchase);
            }

            $purchaseRecord->update(['total' => $purchaseTotal]);

            // Supplier transaction: credit supplier account with total, debit business account (fully paid)
            createTransaction($businessAccount->id, $purchaseDate, 0, $purchaseTotal, "Payment of Purchase No. " . $purchaseRecord->id, $refPurchase);
            createTransaction($supplierAccount->id, $purchaseDate, $purchaseTotal, $purchaseTotal, "Payment of Purchase No. " . $purchaseRecord->id, $refPurchase);

            // Generate Sales (6 sales per month spread across the month)
            for ($s = 1; $s <= 6; $s++) {
                $saleDay = rand(6, $daysInMonth);
                $saleDate = (clone $monthDate)->day($saleDay)->format('Y-m-d');
                $refSale = getRef();

                $saleRecord = sale::create([
                    'customer_id' => $customerAccount->id,
                    'attendant_id' => 1, // Test Attendant
                    'date' => $saleDate,
                    'notes' => "Retail sale batch " . $s,
                    'status' => ($s % 3 == 0) ? 'pending' : 'paid', // 1/3 pending, 2/3 paid
                    'refID' => $refSale,
                ]);

                $saleTotal = 0;
                $saleItems = [
                    ['product' => $petrol, 'qty' => rand(150, 400), 'price' => 375],
                    ['product' => $diesel, 'qty' => rand(100, 300), 'price' => 385],
                    ['product' => $oil, 'qty' => rand(1, 4), 'price' => 300],
                ];

                foreach ($saleItems as $item) {
                    $amount = $item['qty'] * $item['price'];
                    $saleTotal += $amount;

                    sale_details::create([
                        'sale_id' => $saleRecord->id,
                        'product_id' => $item['product']->id,
                        'price' => $item['price'],
                        'qty' => $item['qty'],
                        'amount' => $amount,
                        'date' => $saleDate,
                        'refID' => $refSale,
                    ]);

                    createStock($item['product']->id, 0, $item['qty'], $saleDate, "Sold in " . $saleRecord->id, $refSale);
                }

                $saleRecord->update(['total' => $saleTotal]);

                if ($saleRecord->status == 'paid') {
                    // Paid sale: Debit cash account (credit = saleTotal, debit = 0)
                    createTransaction($businessAccount->id, $saleDate, $saleTotal, 0, "Payment of Sale # " . $saleRecord->id, $refSale);
                    salePayments::create([
                        'sale_id' => $saleRecord->id,
                        'account_id' => $businessAccount->id,
                        'amount' => $saleTotal,
                        'notes' => 'Received cash',
                        'date' => $saleDate,
                        'refID' => $refSale,
                    ]);
                } else {
                    // Pending sale: debit customer account (credit = saleTotal, debit = 0)
                    createTransaction($customerAccount->id, $saleDate, $saleTotal, 0, "Pending Amount of Sale # " . $saleRecord->id, $refSale);
                }
            }

            // Generate Expenses (3 per month)
            for ($e = 1; $e <= 3; $e++) {
                $expenseDay = rand(5, $daysInMonth);
                $expenseDate = (clone $monthDate)->day($expenseDay)->format('Y-m-d');
                $refExpense = getRef();
                $cat = $categories[array_rand($categories)];
                $expenseAmount = rand(3000, 12000);

                expenses::create([
                    'account_id' => $businessAccount->id,
                    'category_id' => $cat->id,
                    'amount' => $expenseAmount,
                    'date' => $expenseDate,
                    'notes' => "Monthly " . $cat->name . " expense",
                    'refID' => $refExpense,
                ]);

                createTransaction($businessAccount->id, $expenseDate, 0, $expenseAmount, 'Expense Category - ' . $cat->name, $refExpense);
            }
        }
    }
}
