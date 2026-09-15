@extends('layout.app')

@section('page-css')
    <style>
        /* Premium dashboard styles */
        .metric-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            position: relative;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }

        /* Card color accents */
        .card-accent-primary::before { background: linear-gradient(180deg, #5c60f5, #888cf8); }
        .card-accent-success::before { background: linear-gradient(180deg, #2cc76f, #5ddc92); }
        .card-accent-warning::before { background: linear-gradient(180deg, #ff9f43, #ffbd80); }
        .card-accent-info::before { background: linear-gradient(180deg, #00cfe8, #55e0f0); }
        .card-accent-secondary::before { background: linear-gradient(180deg, #82868b, #aeb2b7); }
        .card-accent-purple::before { background: linear-gradient(180deg, #a065f9, #c7a4fc); }
        .card-accent-danger::before { background: linear-gradient(180deg, #ea5455, #f18c8e); }

        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        /* Icon background opacity classes */
        .icon-bg-primary { background-color: rgba(92, 96, 245, 0.1); color: #5c60f5; }
        .icon-bg-success { background-color: rgba(44, 199, 111, 0.1); color: #2cc76f; }
        .icon-bg-warning { background-color: rgba(255, 159, 67, 0.1); color: #ff9f43; }
        .icon-bg-info { background-color: rgba(0, 207, 232, 0.1); color: #00cfe8; }
        .icon-bg-secondary { background-color: rgba(130, 134, 139, 0.1); color: #82868b; }
        .icon-bg-purple { background-color: rgba(160, 101, 249, 0.1); color: #a065f9; }
        .icon-bg-danger { background-color: rgba(234, 84, 85, 0.1); color: #ea5455; }

        .metric-title {
            font-size: 13px;
            font-weight: 600;
            color: #82868b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .metric-value {
            font-size: 22px;
            font-weight: 700;
            color: #2f2f3b;
        }

        .chart-card {
            border: none;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            margin-bottom: 24px;
        }

        .chart-card .card-header {
            background: transparent;
            border-bottom: 1px solid #f1f3f4;
            padding: 20px 24px;
        }

        .chart-card .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #2f2f3b;
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
    <!-- Dashboard Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h3 class="mb-1 text-dark f-w-700">Business Dashboard</h3>
            <p class="text-secondary mb-0">Overview of fuel sales, purchases, and cash flows for {{ projectName() }}</p>
        </div>
        <div class="text-secondary f-w-600">
            <i class="ti ti-calendar me-1"></i> {{ date('F Y') }}
        </div>
    </div>

    <!-- Row 1: Primary Month Metrics -->
    <div class="row g-4 mb-4">
        <!-- Month Sales -->
        <div class="col-xl-3 col-md-6 col-sm-12">
            <div class="card metric-card card-accent-primary">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <div class="metric-title">Sales This Month</div>
                        <div class="metric-value">Rs. {{ number_format($currentMonthSales, 2) }}</div>
                    </div>
                    <div class="icon-box icon-bg-primary">
                        <i class="ti ti-chart-bar"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Month Expenses -->
        <div class="col-xl-3 col-md-6 col-sm-12">
            <div class="card metric-card card-accent-warning">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <div class="metric-title">Expenses This Month</div>
                        <div class="metric-value">Rs. {{ number_format($currentMonthExpenses, 2) }}</div>
                    </div>
                    <div class="icon-box icon-bg-warning">
                        <i class="ti ti-receipt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Month Net Profit -->
        <div class="col-xl-3 col-md-6 col-sm-12">
            <div class="card metric-card card-accent-success">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <div class="metric-title">Net Profit This Month</div>
                        <div class="metric-value @if($currentMonthProfit < 0) text-danger @endif">
                            Rs. {{ number_format($currentMonthProfit, 2) }}
                        </div>
                    </div>
                    <div class="icon-box icon-bg-success">
                        <i class="ti ti-piggy-bank"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Accounts Balance -->
        <div class="col-xl-3 col-md-6 col-sm-12">
            <div class="card metric-card card-accent-info">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <div class="metric-title">Cash & Bank Balance</div>
                        <div class="metric-value">Rs. {{ number_format($businessBalance, 2) }}</div>
                    </div>
                    <div class="icon-box icon-bg-info">
                        <i class="ti ti-building-bank"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Secondary / Stock & Balances Metrics -->
    <div class="row g-4 mb-4">
        <!-- Stock Value -->
        <div class="col-md-4 col-sm-12">
            <div class="card metric-card card-accent-secondary">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <div class="metric-title">Current Stock Value</div>
                        <div class="metric-value">Rs. {{ number_format($stockVal, 2) }}</div>
                    </div>
                    <div class="icon-box icon-bg-secondary">
                        <i class="ti ti-box"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Receivables -->
        <div class="col-md-4 col-sm-12">
            <div class="card metric-card card-accent-purple">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <div class="metric-title">Customer Receivables</div>
                        <div class="metric-value">Rs. {{ number_format($customerReceivables, 2) }}</div>
                    </div>
                    <div class="icon-box icon-bg-purple">
                        <i class="ti ti-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supplier Payables -->
        <div class="col-md-4 col-sm-12">
            <div class="card metric-card card-accent-danger">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <div class="metric-title">Supplier Payables</div>
                        <div class="metric-value">Rs. {{ number_format($supplierPayables, 2) }}</div>
                    </div>
                    <div class="icon-box icon-bg-danger">
                        <i class="ti ti-truck-delivery"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Charts -->
    <div class="row">
        <!-- Monthly Sale & Expense Chart -->
        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="card chart-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Monthly Sale & Expense Overview</h5>
                    <span class="badge bg-light-primary text-primary">Last 6 Months</span>
                </div>
                <div class="card-body p-4">
                    <div id="sale-expense-chart-container" style="min-height: 320px;"></div>
                </div>
            </div>
        </div>

        <!-- Monthly Profit Chart -->
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card chart-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Monthly Profit Trend</h5>
                    <span class="badge bg-light-success text-success">Last 6 Months</span>
                </div>
                <div class="card-body p-4">
                    <div id="profit-chart-container" style="min-height: 320px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Data passed from DashboardController
            const categories = @json($months);
            const salesData = @json($salesData);
            const expensesData = @json($expensesData);
            const profitData = @json($profitData);

            // 1. Sales & Expenses Chart Options
            const saleExpenseOptions = {
                series: [
                    {
                        name: 'Sales',
                        data: salesData
                    },
                    {
                        name: 'Expenses',
                        data: expensesData
                    }
                ],
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: { show: false },
                    fontFamily: 'Lexend Deca, sans-serif'
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 6,
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: categories,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            colors: '#6c757d',
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Rs. (Amount)',
                        style: {
                            color: '#6c757d',
                            fontWeight: 600
                        }
                    },
                    labels: {
                        formatter: function (val) {
                            return val >= 1000 ? (val / 1000) + 'k' : val;
                        },
                        style: {
                            colors: '#6c757d',
                            fontSize: '12px'
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                colors: ['#5c60f5', '#ff9f43'],
                grid: {
                    strokeDashArray: 4,
                    borderColor: '#f1f3f4'
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontFamily: 'Lexend Deca, sans-serif',
                    markers: {
                        radius: 12
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "Rs. " + val.toLocaleString();
                        }
                    }
                }
            };

            // Render Sales & Expenses Chart
            const saleExpenseChart = new ApexCharts(
                document.querySelector("#sale-expense-chart-container"), 
                saleExpenseOptions
            );
            saleExpenseChart.render();


            // 2. Net Profit Chart Options
            const profitOptions = {
                series: [{
                    name: 'Net Profit',
                    data: profitData
                }],
                chart: {
                    type: 'area',
                    height: 320,
                    toolbar: { show: false },
                    fontFamily: 'Lexend Deca, sans-serif'
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    colors: ['#2cc76f']
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                xaxis: {
                    categories: categories,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            colors: '#6c757d',
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return val >= 1000 ? (val / 1000) + 'k' : val;
                        },
                        style: {
                            colors: '#6c757d',
                            fontSize: '12px'
                        }
                    }
                },
                colors: ['#2cc76f'],
                grid: {
                    strokeDashArray: 4,
                    borderColor: '#f1f3f4'
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "Rs. " + val.toLocaleString();
                        }
                    }
                }
            };

            // Render Profit Chart
            const profitChart = new ApexCharts(
                document.querySelector("#profit-chart-container"), 
                profitOptions
            );
            profitChart.render();
        });
    </script>
@endsection
