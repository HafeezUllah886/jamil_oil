@extends('layout.app')
@section('content')
    <div class="row">
        <!-- Default Datatable start -->
        <div class="col-12">
            <div class="card ">
                <div class="card-header d-flex justify-content-between">
                    <h5>Edit Account</h5>
                </div>
                <div class="card-body">
                    <div class="app-datatable-default overflow-auto app-scroll">
                        <form action="{{ route('account.update', $account->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="title">Account Title</label>
                                        <input type="text" name="title" id="title" value="{{ $account->title }}"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-12 mt-2" id="catBox">
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="Cash">Cash</option>
                                            <option value="Bank">Bank</option>
                                            <option value="Cheque">Cheque</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-6 mt-2 customer">
                                    <div class="form-group">
                                        <label for="contact">Contact #</label>
                                        <input type="text" name="contact" id="contact" value="{{ $account->contact }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-6 mt-2 customer">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" name="address" id="address" value="{{ $account->address }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="form-group">
                                        <label for="is_active">Status</label>
                                        <select name="is_active" id="is_active" class="form-control">
                                            <option value="1" @selected($account->is_active)>Active</option>
                                            <option value="0" @selected(!$account->is_active)>Inactive</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-secondary w-100">Update</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Default Datatable end -->



    </div>
    <!-- Default Modals -->
@endsection
@section('page-css')
@endsection

@section('page-js')
@section('page-js')
    <script>
        $(".customer").hide();
        checkType();

        function checkType() {
            var type = $("#type").find(":selected").val();

            if (type === "Business") {
                $("#catBox").show();
            } else {
                $("#catBox").hide();
            }

            if (type !== "Business") {
                $(".customer").show();
            } else {
                $(".customer").hide();
            }
        }
    </script>
@endsection
@endsection
