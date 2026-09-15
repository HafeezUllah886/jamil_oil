@extends('layout.app')
@section('content')
    <div class="row">
        <!-- Default Datatable start -->
        <div class="col-12">
            <div class="card ">
                <div class="card-header d-flex justify-content-between">

                    <h5>Transfers</h5>

                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new">Create New</button>
                </div>
                <div class="card-body p-0">
                    <div class="app-datatable-default overflow-auto app-scroll">
                        <table class="display app-data-table default-data-table" id="defaultDatatable">
                            <thead>
                                <tr>
                                    <th width="10px">#</th>
                                    <th>Date</th>
                                    <th class="text-start">From</th>
                                    <th>To</th>
                                    <th>Amount</th>
                                    <th>Notes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transfers as $key => $item)
                                    <tr>
                                        <td class="text-dark">{{ $key + 1 }}</td>
                                        <td class="text-start">{{ $item->date }}</td>
                                        <td>{{ $item->from->title }}</td>
                                        <td>{{ $item->to->title }}</td>
                                        <td>{{ number_format($item->amount, 2) }}</td>
                                        <td>{{ $item->notes }}</td>

                                        <td>
                                            <a href="{{ route('transfer.delete', $item->refID) }}"
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
                    <h5 class="modal-title" id="myModalLabel">Create New Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('transfers.store') }}" method="post" id="transferForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mt-2">
                            <label for="from_id">From | Balance <span id="balance"></span></label>
                            <select name="from_id" id="from_id" onchange="getBalance(this.value);"
                                class="form-control select2">
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->title }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <label for="to_id">To | Balance <span id="balance1"></span></label>
                            <select name="to_id" id="to_id" onchange="getBalance1(this.value);"
                                class="form-control select2">
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->title }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <label for="amount">Amount</label>
                            <input type="number" step="any" name="amount" required value="" min="0"
                                id="amount" class="form-control">
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

        getBalance1($("#from_id").val());

        function getBalance1(id) {
            if (id) {
                $.ajax({
                    url: "/accountbalance/" + id,
                    type: "GET",
                    success: function(response) {
                        if (response.data < 1) {
                            $('#balance1').removeClass('text-success');
                            $('#balance1').addClass('text-danger').text('(Rs. ' + response.data + ')');
                        } else {
                            $('#balance1').removeClass('text-danger');

                            $('#balance1').addClass('text-success').text('(Rs. ' + response.data + ')');
                        }
                    }
                });
            } else {
                $('#balance1').text('');
            }
        }

        $("#transferForm").submit(function(e) {
            if ($('#from_id').val() == $('#to_id').val()) {
                e.preventDefault();
                alert("From Account and To Account must be different.");
            }
        });
    </script>
@endsection
