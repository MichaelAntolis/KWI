<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dumpling;
use App\Models\Sauce;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderSauce;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        $dumplings = Dumpling::all();
        $sauces = Sauce::all();
        return view('kasir.index', compact('dumplings', 'sauces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dumpling_id' => 'required|exists:dumplings,id',
            'quantity' => 'required|integer|min:1',
            'free_sauce_id' => 'required|exists:sauces,id',
            'payment_method' => 'required|in:cash,qris,transfer',
            'extra_sauces' => 'nullable|array',
            'extra_sauces.*.id' => 'required_with:extra_sauces|exists:sauces,id',
            'extra_sauces.*.qty' => 'required_with:extra_sauces|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $dumpling = Dumpling::findOrFail($request->dumpling_id);
            $quantity = $request->quantity;
            $extraSaucArr = $request->extra_sauces ?? [];

            // Hitung harga saus tambahan (total qty saus tambahan x 2000 x porsi)
            $totalExtraSausQty = array_sum(array_column($extraSaucArr, 'qty') ?: [0]);
            $extraSaucePrice = $totalExtraSausQty * 2000 * $quantity; // Rp 2.000 per pcs
            $totalPrice = ($dumpling->price * $quantity) + $extraSaucePrice;

            // Buat order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_price' => $totalPrice,
                'payment_method' => $request->payment_method,
            ]);

            // Buat order detail
            $orderDetail = OrderDetail::create([
                'order_id' => $order->id,
                'dumpling_id' => $dumpling->id,
                'quantity' => $quantity,
            ]);

            // Saus gratis (selalu satu)
            OrderSauce::create([
                'order_detail_id' => $orderDetail->id,
                'sauce_id' => $request->free_sauce_id,
                'is_free' => true,
            ]);

            // Saus tambahan (setiap qty = 1x record, is_free = false)
            if (!empty($extraSaucArr)) {
                foreach ($extraSaucArr as $item) {
                    $qty = intval($item['qty']);
                    for ($i = 1; $i <= $qty; $i++) {
                        OrderSauce::create([
                            'order_detail_id' => $orderDetail->id,
                            'sauce_id' => $item['id'],
                            'is_free' => false,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('kasir.show', $order->id);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Transaksi gagal: ' . $e->getMessage()]);
        }
    }

    public function show(Order $order)
    {
        $order->load('user', 'details.dumpling', 'details.sauces.sauce');
        return view('kasir.struk', compact('order'));
    }
}
