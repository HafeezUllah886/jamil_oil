@extends('layout.app')
@section('content')
    <div class="row">
        <!-- Default Datatable start -->
        <div class="col-md-6  mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>Expense Report</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reportExpenseData') }}" method="get">
                        @csrf
                        <div class="row">

                            <div class="form-group mt-2">
                                <label for="from_date">From Date</label>
                                <input type="date" name="from" required value="{{ firstDayOfMonth() }}" id="from"
                                    class="form-control">
                            </div>

                            <div class="form-group mt-2">
                                <label for="to_date">To Date</label>
                                <input type="date" name="to" required value="{{ date('Y-m-d') }}" id="to"
                                    class="form-control">
                            </div>

                            <div class="form-group mt-2">
                                <label for="categories">Categories</label>
                                <select name="category" id="categories" class="form-control select2" required>
                                    <option value="All">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
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
@endsection
@section('page-css')
    <link href="{{ asset('assets/vendor/select/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page-js')
    <script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Select Categories',
                allowClear: true
            });
        });
    </script>
@endsection
