@extends('layout.app')
@section('content')
    <div class="row">
        <!-- Default Datatable start -->
        <div class="col-12">
            <div class="card ">
                <div class="card-header d-flex justify-content-between">

                    <h5>Daily Sheet</h5>

                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new">Create New</button>
                </div>
                <div class="card-body p-0">
                    <div class="app-datatable-default overflow-auto app-scroll">
                        <table class="display app-data-table default-data-table" id="defaultDatatable">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>From</th>
                                    <th class="text-start">To</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dailysheets as $key => $sheet)
                                    <tr>
                                        <td class="text-dark">{{ $key + 1 }}</td>
                                        <td class="text-start">{{ date('d-m-Y h:i A', strtotime($sheet->from)) }}</td>
                                        <td>{{ date('d-m-Y h:i A', strtotime($sheet->to)) }}</td>

                                        <td>
                                            <a href="{{ route('reportDailySheetView', $sheet->id) }}"
                                                class="btn btn-warning btn-sm">View</a>

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
                    <h5 class="modal-title" id="myModalLabel">Create New Sheet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('reportDailySheetData') }}" method="get">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mt-2">
                            <label for="from">From </label>
                            <input type="datetime-local" name="from" id="from"
                                value="{{ now()->subDay()->setTime(20, 0)->format('Y-m-d\TH:i') }}" class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="to">To </label>
                            <input type="datetime-local" name="to" id="to"
                                value="{{ now()->setTime(20, 0)->format('Y-m-d\TH:i') }}" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Proceed</button>
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

        getBalance($("#from_id").val());

        function getBalance(id) {
            if (id) {
                $.ajax({
                    url: "/accountbalance/" + id,
                    type: "GET",
                    success: function(response) {
                        if (response.data < 1) {
                            $('#balance').removeClass('text-success');
                            $('#balance').addClass('text-danger').text('(Rs. ' + response.data + ')');
                        } else {
                            $('#balance').removeClass('text-danger');

                            $('#balance').addClass('text-success').text('(Rs. ' + response.data + ')');
                        }
                    }
                });
            } else {
                $('#balance').text('');
            }
        }
    </script>
@endsection
