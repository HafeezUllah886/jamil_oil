@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card ">
                <div class="card-header d-flex justify-content-between">
                    <h5>Sales</h5>
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
                                    <th class="text-start">Attendant</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $key => $sale)
                                    <tr>
                                        <td class="text-dark" style="width: 10px;">{{ $key + 1 }}</td>

                                        <td class="text-start">{{ date('d-m-Y', strtotime($sale->date)) }}</td>
                                        <td class="text-start">{{ $sale->customer->title }}</td>
                                        <td class="text-start">{{ $sale->attendant->name }}</td>
                                        <td class="text-end">{{ number_format($sale->total, 2) }}</td>

                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-primary btn-sm px-2" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('sale.show', $sale->id) }}"><i
                                                                class="ti ti-eye me-2 text-secondary"></i> View</a></li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('sale.edit', $sale->id) }}"><i
                                                                class="ti ti-edit me-2 text-secondary"></i> Edit</a></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger"
                                                            href="{{ route('sales.delete', $sale->id) }}"><i
                                                                class="ti ti-trash me-2 text-danger"></i>
                                                            Delete</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Default Datatable end -->



    </div>
    <!-- Default Modals -->

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
            <label for="supplier" class="form-label" style="display: none;">Customer</label>
            <select class="form-control" name="supplier" id="supplier">
                <option value="all">All Customers</option>
                @foreach ($customers as $cust)
                    <option value="{{ $cust->id }}" {{ $customer == $cust->id ? 'selected' : '' }}>
                        {{ $cust->title }}</option>
                @endforeach
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
