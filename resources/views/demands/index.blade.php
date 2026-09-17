@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card ">
                <div class="card-header d-flex justify-content-between">
                    <h5>Demands</h5>
                    <button aria-controls="canvasEnd" class="btn btn-primary" data-bs-target="#canvasEnd"
                        data-bs-toggle="offcanvas" type="button">Filter</button>
                </div>
                <div class="card-body p-0">
                    <div class="app-datatable-default overflow-auto app-scroll">
                        <table class="display app-data-table default-data-table" id="defaultDatatable">
                            <thead>
                                <tr>
                                    <th style="width: 10px;">#</th>
                                    <th class="text-start">Date</th>
                                    <th class="text-start">Customer</th>
                                    <th class="text-start">Status</th>
                                    <th class="text-end">Demand Total</th>
                                    <th class="text-end">Delivery Amount</th>
                                    <th class="text-end">Delivery Charges</th>
                                    <th class="text-end bg-light">Net Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grandDemandTotal = 0;
                                    $grandDeliveryAmount = 0;
                                    $grandDeliveryCharges = 0;
                                    $grandNetTotal = 0;
                                @endphp
                                @foreach ($demands as $key => $demand)
                                    @php
                                        $demandTotal = $demand->details->sum(function ($d) {return $d->qty * $d->price;});
                                        $deliveryAmount = $demand->deliveries->sum('total_amount');
                                        $deliveryCharges = $demand->deliveries->sum('delivery_charges');
                                        $netTotal = $demand->deliveries->sum(function($d) { return $d->total_amount + $d->delivery_charges; });

                                        $grandDemandTotal += $demandTotal;
                                        $grandDeliveryAmount += $deliveryAmount;
                                        $grandDeliveryCharges += $deliveryCharges;
                                        $grandNetTotal += $netTotal;
                                    @endphp
                                    <tr>
                                        <td class="text-dark" style="width: 10px;">{{ $key + 1 }}</td>

                                        <td class="text-start">{{ date('d-m-Y', strtotime($demand->date)) }}</td>
                                        <td class="text-start">{{ $demand->customer->title }}</td>
                                        <td class="text-start">
                                            @if ($demand->status == 'Pending')
                                                <span class="badge bg-warning">{{ $demand->status }}</span>
                                            @elseif($demand->status == 'In Progress')
                                                <span class="badge bg-info">{{ $demand->status }}</span>
                                            @elseif($demand->status == 'Completed')
                                                <span class="badge bg-success">{{ $demand->status }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $demand->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($demandTotal) }}
                                        </td>
                                        <td class="text-end text-success">
                                            {{ number_format($deliveryAmount) }}
                                        </td>
                                        <td class="text-end text-danger">
                                            {{ number_format($deliveryCharges) }}
                                        </td>
                                        <td class="text-end f-w-600 bg-light">
                                            {{ number_format($netTotal) }}
                                        </td>

                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-primary btn-sm px-2" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('demand.show', $demand->id) }}"><i
                                                                class="ti ti-eye me-2 text-secondary"></i> View</a></li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('demand.edit', $demand->id) }}"><i
                                                                class="ti ti-edit me-2 text-secondary"></i> Edit</a></li>
                                                    @if (in_array($demand->status, ['Pending', 'In Progress']))
                                                        <li><a class="dropdown-item text-info"
                                                                href="{{ route('demand.deliver', $demand->id) }}"><i
                                                                    class="ti ti-truck me-2 text-info"></i> Deliver</a></li>
                                                    @endif
                                                    @if($demand->deliveries->count() > 0)
                                                    <li><a class="dropdown-item text-primary" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deliveryHistoryModal{{ $demand->id }}"><i class="ti ti-history me-2 text-primary"></i> Deliveries History</a></li>
                                                    @endif
                                                    <li>
                                                        <a class="dropdown-item text-danger"
                                                            href="{{ route('demand.delete', $demand->id) }}"><i
                                                                class="ti ti-trash me-2 text-danger"></i>
                                                            Delete</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Grand Totals:</th>
                                    <th class="text-end">{{ number_format($grandDemandTotal) }}</th>
                                    <th class="text-end text-success">{{ number_format($grandDeliveryAmount) }}</th>
                                    <th class="text-end text-danger">{{ number_format($grandDeliveryCharges) }}</th>
                                    <th class="text-end bg-light">{{ number_format($grandNetTotal) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Default Datatable end -->



    </div>
    <!-- Default Modals -->

    <!-- Deliveries History Modals -->
    @foreach ($demands as $demand)
        @if($demand->deliveries->count() > 0)
        <div class="modal fade" id="deliveryHistoryModal{{ $demand->id }}" tabindex="-1" aria-labelledby="deliveryHistoryModalLabel{{ $demand->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deliveryHistoryModalLabel{{ $demand->id }}">Delivery History for Demand #{{ $demand->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Vehicle</th>
                                        <th>Products</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($demand->deliveries as $delivery)
                                    <tr>
                                        <td>{{ date('d-m-Y', strtotime($delivery->date)) }}</td>
                                        <td>{{ $delivery->vehicle_no }}</td>
                                        <td>
                                            <ul class="mb-0 ps-3">
                                                @foreach($delivery->details as $det)
                                                    <li>{{ $det->product->name }} ({{ number_format($det->qty) }})</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-end">{{ number_format($delivery->total_amount + $delivery->delivery_charges) }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('demand.delivery.delete', $delivery->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this delivery? This will revert stocks and ledger transactions.')"><i class="ti ti-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach

@section('filter-content')
    <div class="mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
            <label for="fromdate" class="form-label" style="display: none;">From Date</label>
            <input type="date" class="form-control" name="from" id="fromdate" value="{{ $from }}">
        </div>
    </div>
    <div class="mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
            <label for="todate" class="form-label" style="display: none;">To Date</label>
            <input type="date" class="form-control" name="to" id="todate" value="{{ $to }}">
        </div>
    </div>
    <div class="mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="ti ti-user"></i></span>
            <label for="customer" class="form-label" style="display: none;">Customer</label>
            <select class="form-control" name="customer" id="customer">
                <option value="all">All Customers</option>
                @foreach ($customers as $cust)
                    <option value="{{ $cust->id }}" {{ $customer == $cust->id ? 'selected' : '' }}>
                        {{ $cust->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="ti ti-activity"></i></span>
            <label for="status" class="form-label" style="display: none;">Status</label>
            <select class="form-control" name="status" id="status">
                <option value="all">All Status</option>
                <option value="Pending" {{ isset($status) && $status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="In Progress" {{ isset($status) && $status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Completed" {{ isset($status) && $status == 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
    </div>
@endsection

@include('layout.offcan')
@endsection

@section('page-css')
<!-- data table css -->
<link href="{{ asset('assets/vendor/datatable/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page-js')
<!-- data table js -->
<script src="{{ asset('assets/vendor/datatable/jquery-3.5.1.js') }}"></script>
<script src="{{ asset('assets/vendor/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatable/datatable2/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatable/datatable2/jszip.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatable/datatable2/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatable/datatable2/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/vendor/datatable/datatable2/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatable/datatable2/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/js/data_table.js') }}"></script>
@endsection
