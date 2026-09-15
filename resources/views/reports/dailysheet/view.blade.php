@extends('layout.app')
@section('content')
    <div class="container invoice-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-4">
                        <!-- Invoice Header -->
                        <div class="row align-items-center mb-4">
                            <div class="col-sm-6">
                                <div class="mb-2">
                                    <h4 class="text-primary mb-1 f-w-700">{{ projectName() }}</h4>
                                    <address class="text-muted mb-0">
                                        {{ addressLineOne() }}<br>
                                        {{ addressLineTwo() }}
                                    </address>
                                </div>
                            </div>
                            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                <div class="mb-2">
                                    <h5 class="text-primary f-w-700 mb-2">Daily Sheet Report</h5>
                                    <p class="mb-1 text-dark-800">From: <strong
                                            class="text-dark">{{ date('d M Y, h:i A', strtotime($from_date)) }}</strong></p>
                                    <p class="mb-1 text-dark-800">To: <strong
                                            class="text-dark">{{ date('d M Y, h:i A', strtotime($to_date)) }}</strong></p>

                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-2 opacity-20">


                        <!-- Items Table -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="table-active">
                                        <th scope="col" rowspan="2" class="p-1 text-center">#</th>
                                        <th scope="col" rowspan="2" class="p-1">Product</th>
                                        <th scope="col" colspan="4" class="text-center p-1">Stocks</th>
                                        <th scope="col" colspan="4" class="text-center p-1">Sales</th>
                                        <th scope="col" colspan="4" class="text-center p-1">Meters</th>
                                    </tr>
                                    <tr class="table-active">
                                        <th scope="col" class="text-center p-1">Opening</th>
                                        <th scope="col" class="text-center p-1">In</th>
                                        <th scope="col" class="text-center p-1">Out</th>
                                        <th scope="col" class="text-center p-1">Closing</th>
                                        <th scope="col" class="text-center p-1">Qty</th>
                                        <th scope="col" class="text-center p-1">Amount</th>
                                        <th scope="col" class="text-center p-1">Paid</th>
                                        <th scope="col" class="text-center p-1">Pending</th>

                                        <th scope="col" class="text-center p-1">Start</th>
                                        <th scope="col" class="text-center p-1">End</th>
                                        <th scope="col" class="text-center p-1">Total</th>
                                        <th scope="col" class="text-center p-1">Checking</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $key => $product)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="text-start p-1">{{ $product->name }}</td>
                                            <td class="text-center p-1">
                                                {{ $product->opening_stock > 0 ? number_format($product->opening_stock) : '-' }}
                                            </td>
                                            <td class="text-center p-1">
                                                {{ $product->stock_in > 0 ? number_format($product->stock_in) : '-' }}
                                            </td>
                                            <td class="text-center p-1">
                                                {{ $product->stock_out > 0 ? number_format($product->stock_out) : '-' }}
                                            </td>
                                            <td class="text-center p-1">
                                                {{ $product->closing_stock > 0 ? number_format($product->closing_stock) : '-' }}

                                            </td>
                                            <td class="text-center p-1">
                                                {{ $product->sold_qty > 0 ? number_format($product->sold_qty) : '-' }}
                                            </td>
                                            <td class="text-center p-1">
                                                {{ $product->amount > 0 ? number_format($product->amount) : '-' }}
                                            </td>
                                            <td class="text-center p-1">
                                                {{ $product->paid > 0 ? number_format($product->paid) : '-' }}
                                            </td>
                                            <td class="text-center p-1">
                                                {{ $product->pending > 0 ? number_format($product->pending) : '-' }}
                                            </td>
                                            <td class="text-center p-1">
                                                {{ $product->meter_start > 0 ? number_format($product->meter_start) : '-' }}
                                            </td>
                                            <td class="text-center p-1">
                                                {{ $product->meter_end > 0 ? number_format($product->meter_end) : '-' }}
                                            </td>
                                            <td class="text-center p-1">
                                                {{ $product->meter_total > 0 ? number_format($product->meter_total) : '-' }}
                                            </td>
                                            <td class="text-center p-1">
                                                {{ $product->meter_checking > 0 ? number_format($product->meter_checking) : '-' }}
                                            </td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('page-css')
    <style>
        .letter-spacing-1 {
            letter-spacing: 0.06em;
        }

        .bg-success-light {
            background-color: rgba(40, 167, 69, 0.12);
        }

        body.dark .bg-success-light {
            background-color: rgba(40, 167, 69, 0.2);
        }

        body.dark .table-light {
            --bs-table-bg: #2c2f38;
            --bs-table-color: #c8ccd6;
            border-color: #3d4251;
        }

        @media print {
            body {
                background-color: #fff !important;
                color: #000 !important;
            }

            .app-navbar,
            .header-main,
            .footer-container,
            .go-top,
            .invoice-footer,
            #theme-customizer,
            .theme-customizer-container {
                display: none !important;
            }

            .app-content {
                margin-left: 0 !important;
                padding: 0 !important;
                margin-top: 0 !important;
            }

            .card {
                border: 0 !important;
                box-shadow: none !important;
            }

            .card-body {
                padding: 0 !important;
            }

            .container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* Fix table overflow on print */
            .table-responsive {
                overflow: visible !important;
            }

            .table-responsive table {
                width: 100% !important;
                table-layout: auto !important;
            }

            .table-responsive th,
            .table-responsive td {
                width: auto !important;
            }
        }
    </style>
@endsection

@section('page-js')
@endsection
