@extends('layout.app')
@section('content')
    <div class="row">
        <!-- Default Datatable start -->
        <div class="col-12">
            <div class="card ">
                <div class="card-header d-flex justify-content-between">
                    <h5>Stock Adjustments</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new">Create New</button>
                </div>
                <div class="card-body p-0">
                    <div class="app-datatable-default overflow-auto app-scroll">
                        <table class="display app-data-table default-data-table" id="defaultDatatable">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>Qty</th>
                                    <th>Notes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($adjustments as $key => $item)
                                    <tr>
                                        <td class="text-dark">{{ $key + 1 }}</td>
                                        <td class="text-start">{{ $item->date }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td><span
                                                class="badge bg-{{ $item->type == 'Credit' ? 'success' : 'danger' }}">{{ $item->type }}</span>
                                        </td>
                                        <td>{{ number_format($item->qty, 2) }}</td>
                                        <td>{{ $item->notes }}</td>

                                        <td>
                                            <a href="{{ route('stock-adjustment.delete', $item->refID) }}"
                                                class="btn btn-danger btn-sm">Delete</a>

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

    <div id="new" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Create New Adjustment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('stock-adjustments.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mt-2">
                            <label for="product_id">Product</label>
                            <select name="product_id" id="product_id"
                                class="form-control select2">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <label for="type">Type</label>
                            <select name="type" id="type" class="form-control">
                                <option value="Credit">Credit</option>
                                <option value="Debit">Debit</option>

                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <label for="qty">Quantity</label>
                            <input type="number" step="any" name="qty" required value="" min="0" id="qty" class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="date">Date</label>
                            <input type="date" name="date" required value="{{ date('Y-m-d') }}" id="date"
                                class="form-control">
                        </div>

                        <div class="form-group mt-2">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
@section('page-css')
    <!-- data table css -->
    <link href="{{ asset('assets/vendor/datatable/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendor/select/select2.min.css') }}" rel="stylesheet" type="text/css">
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
    <script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

     
    </script>
@endsection
