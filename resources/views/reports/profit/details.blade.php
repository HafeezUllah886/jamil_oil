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
                                    <h5 class="text-primary f-w-700 mb-2">Profit/Loss Report</h5>
                                    <p class="mb-1 text-dark-800">From: <strong
                                            class="text-dark">{{ date('d M Y', strtotime($from)) }}</strong></p>
                                    <p class="mb-1 text-dark-800">To: <strong
                                            class="text-dark">{{ date('d M Y', strtotime($to)) }}</strong></p>

                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-2 opacity-20">

                        <!-- Items Table -->
                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="table-active">
                                        <th scope="col" class="p-1">#</th>
                                        <th scope="col" class="p-1">Product</th>
                                        <th scope="col" class="text-end p-1">Avg Purchase Rate</th>
                                        <th scope="col" class="text-end p-1">Avg Sale Price</th>
                                        <th scope="col" class="text-end p-1">Sold Qty</th>
                                        <th scope="col" class="text-end p-1">Profit / Unit</th>
                                        <th scope="col" class="text-end p-1">Profit</th>
                                        <th scope="col" class="text-end p-1">Stock</th>
                                        <th scope="col" class="text-end p-1">Stock Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($data as $key => $item)
                                        @if ($item['sold'] > 0)
                                            @php
                                                $total += $item['profit'];
                                            @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td class="text-start p-1">{{ $item['name'] }}</td>
                                                <td class="text-end p-1">{{ number_format($item['purchaseRate'], 2) }}</td>
                                                <td class="text-end p-1">{{ number_format($item['saleRate'], 2) }}</td>
                                                <td class="text-end p-1">{{ number_format($item['sold'], 2) }}</td>
                                                <td class="text-end p-1">{{ number_format($item['ppu'], 2) }}</td>
                                                <td class="text-end p-1">{{ number_format($item['profit'], 2) }}</td>
                                                <td class="text-end p-1">{{ number_format($item['stock'], 2) }}</td>
                                                <td class="text-end p-1">{{ number_format($item['stockValue'], 2) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-end p-1">Total</th>
                                        <th class="text-end p-1">{{ number_format($total, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end p-1">Expense</th>
                                        <th class="text-end p-1">{{ number_format($expenses, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end p-1">Net Profit</th>
                                        <th class="text-end p-1">{{ number_format($total - $expenses, 2) }}</th>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="invoice-footer float-end mb-3">
                    <button class="btn btn-primary m-1" onclick="window.print()" type="button"><i
                            class="ti ti-printer"></i> Print
                    </button>
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
