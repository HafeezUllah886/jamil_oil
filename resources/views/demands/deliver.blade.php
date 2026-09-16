@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Deliver Demand #{{ $demand->id }} ({{ $demand->customer->title }})</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('demand.deliverStore', $demand->id) }}" method="post" id="deliverForm">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <th width="30%">Product</th>
                                        <th class="text-center">Demand Qty</th>
                                        <th class="text-center">Delivered Qty</th>
                                        <th class="text-center">Pending Qty</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Delivering Qty</th>
                                        <th class="text-end">Amount</th>
                                    </thead>
                                    <tbody id="products_list">
                                        @php $totalAmount = 0; @endphp
                                        @foreach ($demand->details as $detail)
                                            @php
                                                $delivered_before = \App\Models\DemandDeliveryDetail::whereHas('delivery', function($q) use ($demand) {
                                                    $q->where('demand_id', $demand->id);
                                                })->where('product_id', $detail->product_id)->sum('qty');
                                                $pending = $detail->qty - $delivered_before;
                                            @endphp
                                            <tr>
                                                <td>{{ $detail->product->name }}</td>
                                                <td class="text-center">{{ $detail->qty }}</td>
                                                <td class="text-center">{{ $delivered_before }}</td>
                                                <td class="text-center">{{ $pending }}</td>
                                                <td>
                                                    <input type="number" name="price[]" id="price_{{ $detail->id }}" step="any" value="{{ $detail->price }}" class="form-control text-center" oninput="updateRow({{ $detail->id }})">
                                                </td>
                                                <td>
                                                    <input type="number" name="delivering_qty[]" id="qty_{{ $detail->id }}" step="any" max="{{ $pending }}" value="{{ $pending > 0 ? 0 : 0 }}" class="form-control text-center" oninput="updateRow({{ $detail->id }})">
                                                </td>
                                                <td>
                                                    <input type="number" name="amount[]" id="amount_{{ $detail->id }}" readonly class="form-control text-end" value="0">
                                                    <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-end">Total Products Amount</th>
                                            <th class="text-end" id="totalAmount">0.00</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="col-3 mt-3">
                                <div class="form-group">
                                    <label for="date">Delivery Date</label>
                                    <input type="date" name="date" id="date" required value="{{ date('Y-m-d') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-3 mt-3">
                                <div class="form-group">
                                    <label for="vehicle_no">Vehicle No</label>
                                    <input type="text" name="vehicle_no" id="vehicle_no" class="form-control">
                                </div>
                            </div>
                            <div class="col-3 mt-3">
                                <div class="form-group">
                                    <label for="driver_name">Driver Name</label>
                                    <input type="text" name="driver_name" id="driver_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-3 mt-3">
                                <div class="form-group">
                                    <label for="delivery_charges">Delivery Charges</label>
                                    <input type="number" name="delivery_charges" id="delivery_charges" step="any" value="0" class="form-control" oninput="updateGrandTotal()">
                                </div>
                            </div>

                            <div class="col-12 mt-3 text-end">
                                <h4>Grand Total (Incl. Charges): <span id="grandTotal">0.00</span></h4>
                            </div>

                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary w-100">Submit Delivery</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        function updateRow(id) {
            var qty = parseFloat($("#qty_" + id).val()) || 0;
            var price = parseFloat($("#price_" + id).val()) || 0;
            var amount = qty * price;
            $("#amount_" + id).val(amount.toFixed(2));
            updateGrandTotal();
        }

        function updateGrandTotal() {
            var productsTotal = 0;
            $("input[id^='amount_']").each(function() {
                productsTotal += parseFloat($(this).val()) || 0;
            });
            $("#totalAmount").text(productsTotal.toFixed(2));

            var charges = parseFloat($("#delivery_charges").val()) || 0;
            var grandTotal = productsTotal + charges;
            $("#grandTotal").text(grandTotal.toFixed(2));
        }
    </script>
@endsection
