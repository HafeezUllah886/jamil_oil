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
                                    <h5 class="text-primary f-w-700 mb-2">Stock Details</h5>
                                    <p class="mb-1 text-dark-800">From: <strong
                                            class="text-dark">{{ date('d M Y', strtotime($from)) }}</strong></p>
                                    <p class="mb-1 text-dark-800">To: <strong
                                            class="text-dark">{{ date('d M Y', strtotime($to)) }}</strong></p>

                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-2 opacity-20">

                        <!-- Purchased From Details -->
                        <div class="row align-items-start mb-3 py-2">
                            <div class="col-sm-6">
                                <p class="text-muted f-s-11 text-uppercase f-w-600 mb-1 letter-spacing-1">Product Details
                                </p>
                                <h6 class="text-dark f-w-700 mb-1">{{ $product->name }}</h6>
                                <address class="mb-0 text-muted f-s-13">
                                    Unit: <span class="text-dark">{{ $product->unit }}</span>
                                </address>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-2">

                        <!-- Items Table -->
                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Ref#</th>
                                        <th scope="col" class="text-start">Date</th>
                                        <th scope="col" class="text-start">Notes</th>
                                        <th scope="col" class="text-end text-danger">Debit</th>
                                        <th scope="col" class="text-end text-success">Credit</th>
                                        <th scope="col" class="text-end">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $balance = $pre_balance;
                                    @endphp
                                    <tr>
                                        <td colspan="6" class="text-end p-1"><span
                                                class="text-uppercase">Opening</span></td>
                                        <td class="text-end text-dark fs-6 fw-bold p-1">{{ number_format($pre_balance, 2) }}
                                        </td>
                                    </tr>
                                    @foreach ($stocks as $item)
                                        @php
                                            $balance = $balance + $item->cr - $item->db;
                                        @endphp
                                        <tr>
                                            <td class="p-1 text-center">{{ $loop->iteration }}</td>
                                            <td class="f-w-600 text-dark p-1 text-center">{{ $item->refID }}</td>
                                            <td class="text-start p-1">{{ date('d M Y', strtotime($item->date)) }}</td>
                                            <td class="text-start p-1">{!! $item->notes !!}</td>
                                            <td class="text-end text-danger p-1">{{ number_format($item->db, 2) }}</td>
                                            <td class="text-end text-success p-1">{{ number_format($item->cr, 2) }}</td>
                                            <td class="text-end text-dark p-1">{{ number_format($balance, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="6" class="text-end p-1"><span
                                                class="text-uppercase">Closing</span></td>

                                        <td class="text-end text-dark fs-6 fw-bold p-1">{{ number_format($balance, 2) }}
                                        </td>
                                    </tr>
                                </tbody>

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
