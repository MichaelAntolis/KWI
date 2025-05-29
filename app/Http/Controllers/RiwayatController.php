<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $orders = Order::with('user')
            ->when($q, function ($query) use ($q) {
                $query->where('id', $q)
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$q%"));
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween(DB::raw("DATE(created_at)"), [$startDate, $endDate]);
            })
            ->when($startDate && !$endDate, function ($query) use ($startDate) {
                $query->where(DB::raw("DATE(created_at)"), '>=', $startDate);
            })
            ->when(!$startDate && $endDate, function ($query) use ($endDate) {
                $query->where(DB::raw("DATE(created_at)"), '<=', $endDate);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('riwayat.index', compact('orders', 'q', 'startDate', 'endDate'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'details.dumpling', 'details.sauces.sauce');
        return view('riwayat.show', compact('order'));
    }
}
