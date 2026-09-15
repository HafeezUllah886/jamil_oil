@extends('layout.app')
@section('content')
    <div class="row">
        <!-- Default Datatable start -->
        <div class="col-md-4 mx-auto">
            <div class="card ">
                <div class="card-header d-flex justify-content-between">
                    <h5>Daily Cash Book Report</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reportCashbookData') }}" method="get">
                        @csrf
                        <div class="row">

                            <div class="form-group mt-2">
                                <label for="date">Date</label>
                                <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class=" mt-2">
                            <button type="submit" class="btn btn-primary w-100">View</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Default Datatable end -->



    </div>
    <!-- Default Modals -->
@endsection
