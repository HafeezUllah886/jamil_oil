<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\products;
use App\Models\Demand;
use App\Models\DemandDetail;
use App\Models\DemandDelivery;
use App\Models\DemandDeliveryDetail;
use App\Models\stock;
use App\Models\transactions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemandController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? lastDayOfMonth();
        $customer = $request->customer ?? 'all';

        $demands = Demand::whereBetween('date', [$from, $to])
            ->when($customer != 'all', function ($query) use ($customer) {
                $query->where('customer_id', $customer);
            })
            ->orderby('id', 'desc')
            ->get();

        $customers = accounts::active()->customer()->get();

        return view('demands.index', compact('demands', 'from', 'to', 'customer', 'customers'));
    }

    public function create()
    {
        $products = products::orderby('name', 'asc')->get();
        $customers = accounts::active()->customer()->get();

        return view('demands.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        try {
            if ($request->isNotFilled('id')) {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
            $ref = getRef();
            $demand = Demand::create([
                'customer_id' => $request->customer_id,
                'month' => $request->month,
                'date' => $request->date,
                'notes' => $request->notes,
                'status' => 'Pending',
                'refID' => $ref,
            ]);

            $ids = $request->id;
            foreach ($ids as $key => $id) {
                if ($request->qty[$key] > 0) {
                    DemandDetail::create([
                        'demand_id' => $demand->id,
                        'product_id' => $id,
                        'qty' => $request->qty[$key],
                        'price' => $request->price[$key] ?? 0,
                    ]);
                }
            }
            DB::commit();
            return to_route('demand.index')->with('success', 'Demand Created');
        } catch (Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Demand $demand)
    {
        return view('demands.view', compact('demand'));
    }

    public function edit(Demand $demand)
    {
        $products = products::orderby('name', 'asc')->get();
        $customers = accounts::active()->customer()->get();

        return view('demands.edit', compact('products', 'customers', 'demand'));
    }

    public function update(Request $request, Demand $demand)
    {
        try {
            if ($request->isNotFilled('id')) {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
            
            foreach ($demand->details as $detail) {
                $detail->delete();
            }

            $demand->update([
                'customer_id' => $request->customer_id,
                'month' => $request->month,
                'date' => $request->date,
                'notes' => $request->notes,
            ]);

            $ids = $request->id;
            foreach ($ids as $key => $id) {
                if ($request->qty[$key] > 0) {
                    DemandDetail::create([
                        'demand_id' => $demand->id,
                        'product_id' => $id,
                        'qty' => $request->qty[$key],
                        'price' => $request->price[$key] ?? 0,
                    ]);
                }
            }
            DB::commit();
            return to_route('demand.index')->with('success', 'Demand Updated');
        } catch (Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $demand = Demand::find($id);
            if ($demand->status != 'Pending') {
                throw new Exception('Only Pending demands can be deleted.');
            }
            DB::beginTransaction();
            foreach ($demand->details as $detail) {
                $detail->delete();
            }
            $demand->delete();
            DB::commit();
            return redirect()->route('demand.index')->with('success', 'Demand Deleted');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('demand.index')->with('error', $e->getMessage());
        }
    }

    public function deliverForm(Demand $demand)
    {
        return view('demands.deliver', compact('demand'));
    }

    public function deliverStore(Request $request, Demand $demand)
    {
        try {
            DB::beginTransaction();
            $ref = getRef();
            $total_amount = 0;
            $delivery_charges = $request->delivery_charges ?? 0;

            $delivery = DemandDelivery::create([
                'demand_id' => $demand->id,
                'date' => $request->date,
                'vehicle_no' => $request->vehicle_no,
                'driver_name' => $request->driver_name,
                'delivery_charges' => $delivery_charges,
                'total_amount' => 0,
                'notes' => $request->notes,
                'refID' => $ref,
            ]);

            $ids = $request->detail_id; // from form
            $completedCount = 0;
            $totalProducts = $demand->details->count();

            foreach ($ids as $key => $detail_id) {
                $delivering_qty = $request->delivering_qty[$key];
                if ($delivering_qty > 0) {
                    $detail = DemandDetail::find($detail_id);
                    $delivered_before = DemandDeliveryDetail::whereHas('delivery', function($q) use ($demand) {
                        $q->where('demand_id', $demand->id);
                    })->where('product_id', $detail->product_id)->sum('qty');

                    $pending = $detail->qty - $delivered_before;

                    if ($delivering_qty > $pending) {
                        throw new Exception("Delivering quantity cannot exceed pending quantity for product " . $detail->product->name);
                    }
                    
                    $price = $request->price[$key];
                    $amount = $price * $delivering_qty;
                    $total_amount += $amount;

                    DemandDeliveryDetail::create([
                        'delivery_id' => $delivery->id,
                        'product_id' => $detail->product_id,
                        'qty' => $delivering_qty,
                        'price' => $price,
                        'amount' => $amount,
                        'refID' => $ref,
                    ]);

                    createStock($detail->product_id, 0, $delivering_qty, $request->date, "Delivered for Demand # $demand->id", $ref);
                    
                    if (($delivered_before + $delivering_qty) >= $detail->qty) {
                        $completedCount++;
                    }
                } else {
                    $detail = DemandDetail::find($detail_id);
                    $delivered_before = DemandDeliveryDetail::whereHas('delivery', function($q) use ($demand) {
                        $q->where('demand_id', $demand->id);
                    })->where('product_id', $detail->product_id)->sum('qty');
                    if ($delivered_before >= $detail->qty) {
                        $completedCount++;
                    }
                }
            }

            $delivery->update(['total_amount' => $total_amount]);

            // Add total products amount to ledger
            createTransaction($demand->customer_id, $request->date, $total_amount, 0, "Products Amount for Delivery of Demand # $demand->id", $ref);
            
            // Add delivery charges to ledger separately
            if ($delivery_charges > 0) {
                $ref2 = getRef();
                createTransaction($demand->customer_id, $request->date, $delivery_charges, 0, "Delivery Charges for Demand # $demand->id", $ref2);
            }

            if ($completedCount >= $totalProducts) {
                $demand->update(['status' => 'Delivered']);
            } else {
                $demand->update(['status' => 'In Progress']);
            }

            DB::commit();
            return to_route('demand.index')->with('success', 'Demand Delivered Successfully');
        } catch (Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }
}
