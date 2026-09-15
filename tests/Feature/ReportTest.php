<?php

use App\Models\User;

it('redirects unauthenticated users away from the profit loss report', function () {
    $response = $this->get(route('reports.profit_loss'));

    $response->assertRedirect(route('login'));
});

it('allows authenticated users to view the profit loss report', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('reports.profit_loss'));

    $response->assertStatus(200);
    $response->assertViewIs('finance.reports.profit_loss');
});

it('profit loss report view contains expected sections', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('reports.profit_loss'));

    $response->assertStatus(200);
    $response->assertSee('Income Statement');
    $response->assertSee('Cost of Goods Sold');
    $response->assertSee('NET PROFIT / (LOSS)');
    $response->assertSee('Operating Expenses');
    $response->assertSee('Product Inventory & Profit Contribution');
});

it('profit loss report accepts date range parameters', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('reports.profit_loss', [
        'from' => '2024-01-01',
        'to' => '2024-01-31',
    ]));

    $response->assertStatus(200);
    $response->assertViewHas('from', '2024-01-01');
    $response->assertViewHas('to', '2024-01-31');
});

it('profit loss report passes all required view data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('reports.profit_loss'));

    $response->assertStatus(200);
    $response->assertViewHasAll([
        'from',
        'to',
        'productBreakdown',
        'totalSales',
        'totalPurchases',
        'totalOpeningStockValue',
        'totalClosingStockValue',
        'totalCogs',
        'totalGrossProfit',
        'expensesByCategory',
        'totalExpenses',
        'netProfit',
    ]);
});
