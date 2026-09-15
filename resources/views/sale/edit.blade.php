@extends('layout.app')
@section('content')
    <script>
        var existingProducts = [];

        @foreach ($sale->details as $product)
            @php
                $product_id = $product->product_id;
            @endphp
            existingProducts.push({{ $product_id }});
        @endforeach
    </script>
    <div class="row">
        <div class="col-12">
            <div class="card ">
                <div class="card-header d-flex justify-content-between">
                    <h5>Edit Sale</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('sale.update', $sale->id) }}" method="post" id="saleForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="product">Product</label>
                                    <select name="product" class="w-100" id="product">
                                        <option value=""></option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <th width="30%">Item</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Amount</th>
                                        <th></th>
                                    </thead>
                                    <tbody id="products_list">
                                        @foreach ($sale->details as $item)
                                            @php
                                                $product_id = $item->product_id;

                                            @endphp
                                            <tr id="row_{{ $product_id }}">
                                                <td class="p-1">{{ $item->product->name }}</td>
                                                <td class="p-0"><input type="number" name="price[]" step="any"
                                                        value="{{ $item->price }}" min="0"
                                                        class="form-control form-control-sm text-center p-1"
                                                        id="price_{{ $product_id }}"></td>
                                                <td class="p-0"><input type="number" name="qty[]"
                                                        oninput="updateChanges({{ $product_id }})" min="0"
                                                        step="any" value="{{ $item->qty }}"
                                                        class="form-control form-control-sm text-center p-1"
                                                        id="qty_{{ $product_id }}"></td>
                                                <td class="p-0"><input type="number" name="amount[]" min="0.1"
                                                        readonly required step="any" value="{{ $item->amount }}"
                                                        class="form-control form-control-sm text-center p-1"
                                                        id="amount_{{ $product_id }}"></td>
                                                <td class="p-0"> <span class="btn btn-sm btn-danger"
                                                        onclick="deleteRow({{ $product_id }})">X</span> </td>
                                                <input type="hidden" name="id[]" value="{{ $product_id }}">
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Total</th>
                                            <th class="text-end" id="totalAmount">{{ number_format($sale->total, 2) }}</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="attendant">Attendant</label>
                                    <select name="attendant" id="attendant" required class="select2 w-100">
                                        <option value=""></option>
                                        @foreach ($attendants as $attendant)
                                            <option value="{{ $attendant->id }}"
                                                {{ $attendant->id == $sale->attendant_id ? 'selected' : '' }}>
                                                {{ $attendant->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" id="date" required
                                        value="{{ date('Y-m-d', strtotime($sale->date)) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="customer">Customer</label>
                                    <select name="customer_id" id="customer_id" required class="select2 w-100">
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ $customer->id == $sale->customer_id ? 'selected' : '' }}>
                                                {{ $customer->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="status">Payment Status</label>
                                    <select name="status" id="status1" onchange="checkStatus(this.value)"
                                        class="form-control">
                                        <option value="paid" {{ $sale->status == 'paid' ? 'selected' : '' }}>Paid
                                        </option>
                                        <option value="pending" {{ $sale->status == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12" id="accounts">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <th>Account</th>
                                        <th class="text-center">Notes</th>
                                        <th class="text-center">Amount</th>
                                    </thead>
                                    <tbody id="accounts_list">
                                        @foreach ($accounts as $account)
                                            @php
                                                $payment = $sale->payments->where('account_id', $account->id)->first();
                                                $notes = $payment ? $payment->notes : '';
                                                $amount = $payment ? $payment->amount : 0;
                                            @endphp
                                            <input type="hidden" name="account_id[]" value="{{ $account->id }}">
                                            <tr>
                                                <td>{{ $account->title }}</td>
                                                <td><input type="text" name="payment_notes[]" class="form-control"
                                                        value="{{ $notes }}"></td>
                                                <td><input type="number" name="payment_amount[]"
                                                        id="paymnet_amount_{{ $account->id }}"
                                                        oninput="calculatePayment()" value="{{ $amount }}"
                                                        class="form-control text-center"></td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-end">Total</th>
                                            <th class="text-center" id="totalPayment">
                                                {{ $sale->payments->sum('amount') }}
                                            </th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" cols="30" rows="5">{{ $sale->notes }}</textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Update Sale</button>
                            </div>
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
            $('.select2').select2();
            $('#product').select2({
                placeholder: "Select a Product",
                allowClear: true,
                width: '100%'
            });
        });

        $("#product").on('select2:select', function(e) {
            var value = e.params.data.id;
            if (value) {
                getSingleProduct(value);
                $(this).val(null).trigger('change');
                $(this).select2('open');
            }
        });


        function getSingleProduct(id) {
            $.ajax({
                url: "{{ url('purchases/getproduct/') }}/" + id,
                method: "GET",
                success: function(product) {
                    let found = $.grep(existingProducts, function(element) {
                        return element === product.id;
                    });
                    if (found.length > 0) {

                    } else {

                        var id = product.id;
                        var html = '<tr id="row_' + id + '">';
                        html += '<td class="p-1">' + product.name + '</td>';

                        html += '<td class="p-0"><input type="number" name="price[]" step="any" value="' +
                            product.price +
                            '" min="0" class="form-control form-control-sm text-center p-1" id="price_' + id +
                            '"></td>';
                        html +=
                            '<td class="p-0"><input type="number" name="qty[]" min="0.1" oninput="updateChanges(' +
                            id +
                            ')" min="0" step="any" value="0" class="form-control form-control-sm text-center p-1" id="qty_' +
                            id + '"></td>';
                        html +=
                            '<td class="p-0"><input type="number" name="amount[]" min="0.1" readonly required step="any" value="1" class="form-control form-control-sm text-center p-1" id="amount_' +
                            id + '"></td>';
                        html += '<td class="p-0"> <span class="btn btn-sm btn-danger" onclick="deleteRow(' +
                            id + ')">X</span> </td>';
                        html += '<input type="hidden" name="id[]" value="' + id + '">';
                        html += '</tr>';
                        $("#products_list").prepend(html);
                        existingProducts.push(id);
                        updateChanges(id);
                    }
                }
            });
        }

        function updateChanges(id) {
            var qty = parseFloat($("#qty_" + id).val());
            var price = parseFloat($("#price_" + id).val());
            var amount = qty * price;
            $("#amount_" + id).val(amount.toFixed(2));
            updateTotal();
        }

        function updateTotal() {
            var total = 0;
            $("input[id^='amount_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                total += parseFloat(inputValue);
            });

            $("#totalAmount").html(total.toFixed(2));
        }

        function deleteRow(id) {
            existingProducts = $.grep(existingProducts, function(value) {
                return value !== id;
            });
            $('#row_' + id).remove();
            updateTotal();
        }

        function checkStatus(status) {
            if (status == 'pending') {
                $('#accounts').hide();
            } else {
                $('#accounts').show();
            }
        }

        function calculatePayment() {
            var total = 0;
            $("input[id^='paymnet_amount_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                total += parseFloat(inputValue);
            });

            $("#totalPayment").html(total.toFixed(2));
        }

        $("#saleForm").submit(function(e) {
            var status = $('#status1').val();
            if (status != 'pending') {
                var total = parseFloat($("#totalAmount").text());
                var payment = parseFloat($("#totalPayment").text());
                if (total != payment) {
                    e.preventDefault();
                    alert("Total Amount and Total Payment must be equal.");
                }
            }
        });
    </script>
@endsection
