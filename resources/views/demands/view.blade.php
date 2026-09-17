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
                                    <h5 class="text-primary f-w-700 mb-2">DEMAND RECEIPT</h5>
                                    <p class="mb-1 text-dark-800">Receipt No. <strong
                                            class="text-dark">#{{ $demand->id }}</strong></p>
                                    <p class="mb-0 text-dark-800">Date <strong
                                            class="text-dark">{{ date('d M Y', strtotime($demand->date)) }}</strong></p>
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-2 opacity-20">

                        <!-- Purchased From Details -->
                        <div class="row align-items-start mb-3 py-2">
                            <div class="col-sm-6">
                                <p class="text-muted f-s-11 text-uppercase f-w-600 mb-1 letter-spacing-1">Demand Type / Ordered
                                    By</p>
                                <h6 class="text-dark f-w-700 mb-1">{{ $demand->customer->title }}</h6>
                                <address class="mb-0 text-muted f-s-13">
                                    {{ $demand->customer->address }} | {{ $demand->customer->contact }}
                                </address>
                            </div>
                            <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
                                <p class="text-muted f-s-11 text-uppercase f-w-600 mb-1 letter-spacing-1">Demand Month</p>
                                <p class="mb-1 f-s-13 text-dark-800"><strong class="text-dark">{{ date('F Y', strtotime($demand->month)) }}</strong></p>
                                <p class="text-muted f-s-11 text-uppercase f-w-600 mb-1 letter-spacing-1">Status</p>
                                <p class="mb-1 f-s-13 text-dark-800"><strong class="badge bg-primary text-white">{{ $demand->status }}</strong></p>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-2">

                        <!-- Items Table -->
                        <h6 class="mt-4 mb-2 f-w-700 text-primary">Demand Products Summary</h6>
                        <div class="table-responsive mt-2">
                            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 50px;">No</th>
                                        <th scope="col">Product</th>
                                        <th scope="col" class="text-center">Unit</th>
                                        <th scope="col" class="text-end">Price</th>
                                        <th scope="col" class="text-end">Demanded Qty</th>
                                        <th scope="col" class="text-end bg-success-light">Delivered Qty</th>
                                        <th scope="col" class="text-end text-danger">Pending Qty</th>
                                        <th scope="col" class="text-end">Demanded Amt</th>
                                        <th scope="col" class="text-end bg-success-light">Delivered Amt</th>
                                        <th scope="col" class="text-end text-danger">Pending Amt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalDemandedAmt = 0;
                                        $totalDeliveredAmt = 0;
                                        $totalPendingAmt = 0;
                                    @endphp
                                    @foreach ($demand->details as $item)
                                        @php
                                            $deliveredQty = $demand->deliveries->flatMap->details->where('product_id', $item->product_id)->sum('qty');
                                            $pendingQty = $item->qty - $deliveredQty;

                                            $demandedAmt = $item->qty * $item->price;
                                            $deliveredAmt = $deliveredQty * $item->price;
                                            $pendingAmt = $pendingQty * $item->price;

                                            $totalDemandedAmt += $demandedAmt;
                                            $totalDeliveredAmt += $deliveredAmt;
                                            $totalPendingAmt += $pendingAmt;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="f-w-600 text-dark">{{ $item->product->name }}</td>
                                            <td class="text-center"><span class="badge bg-light text-dark">{{ $item->product->unit }}</span></td>
                                            <td class="text-end">{{ number_format($item->price, 2) }}</td>
                                            <td class="text-end">{{ number_format($item->qty, 2) }}</td>
                                            <td class="text-end bg-success-light">{{ number_format($deliveredQty, 2) }}</td>
                                            <td class="text-end text-danger">{{ number_format($pendingQty, 2) }}</td>
                                            <td class="text-end text-dark">{{ number_format($demandedAmt, 2) }}</td>
                                            <td class="text-end bg-success-light">{{ number_format($deliveredAmt, 2) }}</td>
                                            <td class="text-end text-danger">{{ number_format($pendingAmt, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light f-w-700 text-dark">
                                        <td colspan="7" class="text-end border-top">Totals</td>
                                        <td class="text-end text-primary f-w-700 border-top">{{ number_format($totalDemandedAmt, 2) }}</td>
                                        <td class="text-end text-success f-w-700 border-top">{{ number_format($totalDeliveredAmt, 2) }}</td>
                                        <td class="text-end text-danger f-w-700 border-top">{{ number_format($totalPendingAmt, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Deliveries Table -->
                        @if($demand->deliveries->count() > 0)
                            <h6 class="mt-5 mb-2 f-w-700 text-primary">Delivery Details</h6>
                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-striped align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col"># ID</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Vehicle No</th>
                                            <th scope="col">Driver Name</th>
                                            <th scope="col">Products Delivered</th>
                                            <th scope="col" class="text-end">Total Amount</th>
                                            <th scope="col" class="text-end">Delivery Charges</th>
                                            <th scope="col" class="text-end">Net Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($demand->deliveries as $delivery)
                                            <tr>
                                                <td><strong>#{{ $delivery->id }}</strong></td>
                                                <td>{{ date('d M Y', strtotime($delivery->date)) }}</td>
                                                <td>{{ $delivery->vehicle_no }}</td>
                                                <td>{{ $delivery->driver_name }}</td>
                                                <td>
                                                    <ul class="mb-0 ps-3">
                                                        @foreach($delivery->details as $dDetail)
                                                            <li>{{ $dDetail->product->name }} : <strong>{{ number_format($dDetail->qty, 2) }}</strong> <span class="text-muted f-s-12">({{ number_format($dDetail->price, 2) }} each)</span></li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td class="text-end">{{ number_format($delivery->total_amount, 2) }}</td>
                                                <td class="text-end">{{ number_format($delivery->delivery_charges, 2) }}</td>
                                                <td class="text-end text-dark f-w-700">{{ number_format($delivery->total_amount + $delivery->delivery_charges, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-light f-w-700 text-dark">
                                            <td colspan="5" class="text-end border-top">Grand Totals</td>
                                            <td class="text-end border-top">{{ number_format($demand->deliveries->sum('total_amount'), 2) }}</td>
                                            <td class="text-end border-top">{{ number_format($demand->deliveries->sum('delivery_charges'), 2) }}</td>
                                            <td class="text-end text-primary f-w-700 border-top">{{ number_format($demand->deliveries->sum(function($d) { return $d->total_amount + $d->delivery_charges; }), 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer p-4">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6"></div>
                            <div class="col-lg-6 col-md-6 col-sm-6 text-end"><strong class="text-dark">Notes</strong>
                                {{ $demand->notes }}
                            </div>
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
            background-color: rgba(40, 167, 69, 0.12) !important;
        }

        body.dark .bg-success-light {
            background-color: rgba(40, 167, 69, 0.2) !important;
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
