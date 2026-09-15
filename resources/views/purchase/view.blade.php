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
                                    <h5 class="text-primary f-w-700 mb-2">PURCHASE RECEIPT</h5>
                                    <p class="mb-1 text-dark-800">Receipt No. <strong
                                            class="text-dark">#{{ $purchase->id }}</strong></p>
                                    <p class="mb-1 text-dark-800">Invoice No. <strong
                                            class="text-dark">{{ $purchase->inv ?? 'N/A' }}</strong></p>
                                    <p class="mb-0 text-dark-800">Date <strong
                                            class="text-dark">{{ date('d M Y', strtotime($purchase->date)) }}</strong></p>
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-2 opacity-20">

                        <!-- Purchased From Details -->
                        <div class="row align-items-start mb-3 py-2">
                            <div class="col-sm-6">
                                <p class="text-muted f-s-11 text-uppercase f-w-600 mb-1 letter-spacing-1">Purchased From</p>
                                <h6 class="text-dark f-w-700 mb-1">{{ $purchase->supplier->title }}</h6>
                                <address class="mb-0 text-muted f-s-13">
                                    {{ $purchase->supplier->address }} | {{ $purchase->supplier->contact }}
                                </address>
                            </div>
                            {{-- <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
                                <p class="text-muted f-s-11 text-uppercase f-w-600 mb-1 letter-spacing-1">Payment Info</p>
                                <p class="mb-1 f-s-13 text-dark-800">Status <strong class="badge bg-success-light text-success ms-1">Paid</strong></p>
                                <p class="mb-0 f-s-13 text-dark-800">Total Amount <strong class="text-primary f-w-700">{{ number_format($purchase->total, 2) }}</strong></p>
                            </div> --}}
                        </div>

                        <!-- Divider -->
                        <hr class="my-2">

                        <!-- Items Table -->
                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 60px;">No</th>
                                        <th scope="col" style="width: 300px;">Product</th>
                                        <th scope="col" class="text-end" style="width: 150px;">Price</th>
                                        <th scope="col" class="text-end" style="width: 150px;">Quantity</th>
                                        <th scope="col" style="width: 100px;">Unit</th>
                                        <th scope="col" class="text-end" style="width: 180px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase->details as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="f-w-600 text-dark">{{ $item->product->name }}</td>
                                            <td class="text-end">{{ number_format($item->price, 2) }}</td>
                                            <td class="text-end">{{ number_format($item->qty, 2) }}</td>
                                            <td><span class="badge bg-light text-dark">{{ $item->product->unit }}</span>
                                            </td>
                                            <td class="text-end text-dark">{{ number_format($item->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light f-w-700 text-dark">
                                        <td colspan="5" class="text-end border-top">Grand Total</td>
                                        <td class="text-end text-primary f-w-700 border-top f-s-16">
                                            {{ number_format($purchase->total, 2) }}</td>
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
