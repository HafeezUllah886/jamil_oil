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
                                    <h5 class="text-primary f-w-700 mb-2">Daily Cash Book</h5>
                                    <p class="mb-1 text-dark-800">Date: <strong
                                            class="text-dark">{{ date('d M Y', strtotime($date)) }}</strong></p>

                                </div>
                            </div>

                        </div>
                        @php
                            $total_cr = $cr_trans->sum('cr');
                            $total_db = $db_trans->sum('db');
                            $closingBalance = $pre_balance + $total_cr - $total_db;
                        @endphp

                        <!-- Divider -->
                        <hr class="my-2 opacity-20">
                        <div class="row align-items-start mb-3 py-2">
                            <div class="col-sm-3">
                                <p class="text-muted f-s-11 text-uppercase f-w-600 mb-1 letter-spacing-1">Opening Balance
                                </p>
                                <h6 class="text-dark f-w-700 mb-1">{{ number_format($pre_balance, 2) }}</h6>
                            </div>
                            <div class="col-sm-3">
                                <p class="text-muted f-s-11 text-uppercase f-w-600 mb-1 letter-spacing-1">Total Credits
                                </p>
                                <h6 class="text-dark f-w-700 mb-1 text-success">{{ number_format($total_cr, 2) }}</h6>
                            </div>
                            <div class="col-sm-3">
                                <p class="text-muted f-s-11 text-uppercase f-w-600 mb-1 letter-spacing-1">Total Debits
                                </p>
                                <h6 class="text-dark f-w-700 mb-1 text-danger">{{ number_format($total_db, 2) }}</h6>
                            </div>
                            <div class="col-sm-3">
                                <p class="text-muted f-s-11 text-uppercase f-w-600 mb-1 letter-spacing-1">Closing Balance
                                </p>
                                <h6 class="text-dark f-w-700 mb-1">{{ number_format($closingBalance, 2) }}</h6>
                            </div>

                        </div>
                        <hr class="my-2 opacity-20">
                        <div class="col-lg-12">
                            <div class="card-header">
                                <h6 class="m-0">Credits / Inflow</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="m-0 p-1" style="width: 50px;">#</th>
                                                <th scope="col" class="m-0 p-1">Ref #</th>
                                                <th scope="col" class="text-start m-0 p-1">Account</th>
                                                <th scope="col" class="text-start m-0 p-1">Notes</th>
                                                <th scope="col" class="text-end m-0 p-1">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cr_trans as $key => $cr_tran)
                                                <tr class="border-1">
                                                    <td class="m-0 p-0">{{ $key + 1 }}</td>
                                                    <td class="m-0 p-0">{{ $cr_tran->refID }}</td>
                                                    <td class="text-start m-0 p-0">{{ $cr_tran->account->title }}</td>
                                                    <td class="text-start m-0 p-0">{!! $cr_tran->notes !!}</td>
                                                    <td class="text-end m-0 p-0">{{ number_format($cr_tran->cr, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end">Total Credits</th>
                                                <th class="text-end">{{ number_format($cr_trans->sum('cr'), 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table><!--end table-->
                                </div>

                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-header">
                                <h6 class="m-0">Debits / Outflow</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="m-0 p-1" style="width: 50px;">#</th>
                                                <th scope="col" class="m-0 p-1">Ref #</th>
                                                <th scope="col" class="text-start m-0 p-1">Account</th>
                                                <th scope="col" class="text-start m-0 p-1">Notes</th>
                                                <th scope="col" class="text-end m-0 p-1">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($db_trans as $key => $db_tran)
                                                <tr class="border-1">
                                                    <td class="m-0 p-0">{{ $key + 1 }}</td>
                                                    <td class="m-0 p-0">{{ $db_tran->refID }}</td>
                                                    <td class="text-start m-0 p-0">{{ $db_tran->account->title }}</td>
                                                    <td class="text-start m-0 p-0">{!! $db_tran->notes !!}</td>
                                                    <td class="text-end m-0 p-0">{{ number_format($db_tran->db, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end">Total Debits</th>
                                                <th class="text-end">{{ number_format($db_trans->sum('db'), 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table><!--end table-->
                                </div>

                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
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
