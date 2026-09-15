@extends('layout.app')
@section('content')
    <div class="row">
        <!-- Default Datatable start -->
        <div class="col-12">
            <div class="card ">
                <div class="card-header d-flex justify-content-between">
                    <h5>Expense Categories</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new">Create New</button>
                </div>
                <div class="card-body p-0">
                    <div class="app-datatable-default overflow-auto app-scroll">
                        <table class="display app-data-table default-data-table" id="defaultDatatable">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cats as $key => $item)
                                    <tr>
                                        <td class="text-dark">{{ $key + 1 }}</td>
                                        <td class="text-start">{{ $item->name }}</td>
                                        <td>
                                            <a data-bs-toggle="modal" data-bs-target="#edit{{ $item->id }}"
                                                class="btn btn-info btn-sm">Edit</a>

                                        </td>
                                        <div id="edit{{ $item->id }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Edit Expense Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('expensesCategories.update', $item->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group mt-2">
                            <label for="name">Expense Category Name</label>
                            <input type="text" name="name" id="name" required value="{{ $item->name }}" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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
                    <h5 class="modal-title" id="myModalLabel">Create New Expense Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('expensesCategories.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mt-2">
                            <label for="name">Expense Category Name</label>
                            <input type="text" name="name" id="name" required value="" class="form-control">
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
@endsection
