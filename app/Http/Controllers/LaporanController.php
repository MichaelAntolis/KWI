<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Dumpling;
use App\Models\Sauce;
use App\Models\Cost;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Total transaksi & pendapatan
        $totalOrder = Order::count();
        $totalIncome = Order::sum('total_price');

        // Total pengeluaran (cost)
        $totalCost = Cost::sum('total_price');

        // Profit = Pendapatan - Pengeluaran
        $totalProfit = $totalIncome - $totalCost;

        // Filter tanggal untuk penjualan
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query penjualan dengan filter tanggal
        $salesQuery = Order::select(
            DB::raw("DATE(created_at) as date"),
            DB::raw("COUNT(*) as total_order"),
            DB::raw("SUM(total_price) as total_income")
        );

        // Query pengeluaran dengan filter tanggal yang sama
        $costsQuery = Cost::select(
            DB::raw("DATE(purchased_date) as date"),
            DB::raw("SUM(total_price) as total_cost")
        );

        // Jika ada filter tanggal
        if ($startDate && $endDate) {
            $salesQuery->whereBetween(DB::raw("DATE(created_at)"), [$startDate, $endDate]);
            $costsQuery->whereBetween(DB::raw("DATE(purchased_date)"), [$startDate, $endDate]);
        } elseif ($startDate) {
            $salesQuery->where(DB::raw("DATE(created_at)"), '>=', $startDate);
            $costsQuery->where(DB::raw("DATE(purchased_date)"), '>=', $startDate);
        } elseif ($endDate) {
            $salesQuery->where(DB::raw("DATE(created_at)"), '<=', $endDate);
            $costsQuery->where(DB::raw("DATE(purchased_date)"), '<=', $endDate);
        }
        // PERBAIKAN: Jika tidak ada filter tanggal, tampilkan semua data (tidak perlu where clause tambahan)

        $salesPerDay = $salesQuery
            ->groupBy(DB::raw("DATE(created_at)"))
            ->orderBy('date', 'desc')
            ->get();

        $costsPerDay = $costsQuery
            ->groupBy(DB::raw("DATE(purchased_date)"))
            ->orderBy('date', 'desc')
            ->get()
            ->keyBy('date');

        // PERBAIKAN: Gabungkan data penjualan dan pengeluaran per hari
        // Sekarang akan mengambil semua tanggal unik dari sales dan costs
        $allDates = collect();

        // Tambahkan semua tanggal dari sales
        $salesPerDay->each(function ($sale) use ($allDates) {
            $allDates->push($sale->date);
        });

        // Tambahkan semua tanggal dari costs yang belum ada
        $costsPerDay->each(function ($cost, $date) use ($allDates) {
            if (!$allDates->contains($date)) {
                $allDates->push($date);
            }
        });

        // Urutkan tanggal secara descending
        $allDates = $allDates->unique()->sortDesc();

        // Buat laporan harian lengkap untuk semua tanggal
        $dailyReport = $allDates->map(function ($date) use ($salesPerDay, $costsPerDay) {
            $sale = $salesPerDay->firstWhere('date', $date);
            $cost = $costsPerDay->get($date);

            $totalIncome = $sale ? $sale->total_income : 0;
            $totalCost = $cost ? $cost->total_cost : 0;
            $totalOrder = $sale ? $sale->total_order : 0;

            return (object) [
                'date' => $date,
                'total_order' => $totalOrder,
                'total_income' => $totalIncome,
                'total_cost' => $totalCost,
                'profit' => $totalIncome - $totalCost
            ];
        });

        // Mendapatkan total penjualan per produk (dumpling)
        $dumplingsSold = DB::table('order_details')
            ->join('dumplings', 'dumplings.id', '=', 'order_details.dumpling_id')
            ->select(
                'dumplings.name as dumpling_name',
                DB::raw('SUM(order_details.quantity) as total_sold')
            )
            ->groupBy('dumplings.name')
            ->get();

        // Mendapatkan total penjualan per saus
        $saucesSold = DB::table('order_sauces')
            ->join('sauces', 'sauces.id', '=', 'order_sauces.sauce_id')
            ->select(
                'sauces.name as sauce_name',
                DB::raw('COUNT(order_sauces.id) as total_sold')
            )
            ->groupBy('sauces.name')
            ->get();

        // Menggabungkan kedua hasil penjualan (dumpling dan saus) menjadi satu koleksi
        $totalItemsSold = $dumplingsSold->merge($saucesSold);

        // Mendapatkan produk dumpling terlaris
        $bestDumpling = $dumplingsSold->sortByDesc('total_sold')->first();

        // Mendapatkan produk saus terlaris
        $bestSauce = $saucesSold->sortByDesc('total_sold')->first();

        // Statistik metode pembayaran
        $paymentStats = Order::select('payment_method', DB::raw('COUNT(*) as total'))
            ->groupBy('payment_method')
            ->get()
            ->mapWithKeys(function ($item) {
                $labels = [
                    'cash' => 'Tunai',
                    'qris' => 'QRIS',
                    'transfer' => 'Transfer Bank'
                ];
                return [$labels[$item->payment_method] ?? $item->payment_method => $item->total];
            });

        // Pastikan semua metode pembayaran ada (dengan nilai 0 jika tidak ada)
        $paymentMethods = [
            'Tunai' => $paymentStats->get('Tunai', 0),
            'QRIS' => $paymentStats->get('QRIS', 0),
            'Transfer Bank' => $paymentStats->get('Transfer Bank', 0)
        ];

        // Pendapatan per metode pembayaran
        $paymentIncomeStats = Order::select('payment_method', DB::raw('SUM(total_price) as total_income'))
            ->groupBy('payment_method')
            ->get()
            ->mapWithKeys(function ($item) {
                $labels = [
                    'cash' => 'Tunai',
                    'qris' => 'QRIS',
                    'transfer' => 'Transfer Bank'
                ];
                return [$labels[$item->payment_method] ?? $item->payment_method => $item->total_income];
            });

        // Pastikan semua metode pembayaran ada (dengan nilai 0 jika tidak ada)
        $paymentIncome = [
            'Tunai' => $paymentIncomeStats->get('Tunai', 0),
            'QRIS' => $paymentIncomeStats->get('QRIS', 0),
            'Transfer Bank' => $paymentIncomeStats->get('Transfer Bank', 0)
        ];

        // Pengeluaran per user (cost per user)
        $costPerUser = Cost::select('users.name', DB::raw('SUM(costs.total_price) as total_cost'))
            ->join('users', 'users.id', '=', 'costs.user_id')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween(DB::raw("DATE(costs.purchased_date)"), [$startDate, $endDate]);
            })
            ->when($startDate && !$endDate, function ($query) use ($startDate) {
                $query->where(DB::raw("DATE(costs.purchased_date)"), '>=', $startDate);
            })
            ->when(!$startDate && $endDate, function ($query) use ($endDate) {
                $query->where(DB::raw("DATE(costs.purchased_date)"), '<=', $endDate);
            })
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_cost', 'desc')
            ->get();

        // Top 5 pengeluaran terbesar
        $topCosts = Cost::with('user')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween(DB::raw("DATE(purchased_date)"), [$startDate, $endDate]);
            })
            ->when($startDate && !$endDate, function ($query) use ($startDate) {
                $query->where(DB::raw("DATE(purchased_date)"), '>=', $startDate);
            })
            ->when(!$startDate && $endDate, function ($query) use ($endDate) {
                $query->where(DB::raw("DATE(purchased_date)"), '<=', $endDate);
            })
            ->orderBy('total_price', 'desc')
            ->limit(5)
            ->get();

        return view('laporan.index', compact(
            'totalOrder',
            'totalIncome',
            'totalCost',
            'totalProfit',
            'dailyReport', // sudah gabungan sales + costs per day untuk semua tanggal
            'totalItemsSold',
            'bestDumpling',
            'bestSauce',
            'paymentMethods',
            'paymentIncome',
            'costPerUser',
            'topCosts',
            'startDate',
            'endDate'
        ));
    }

    /**
     * API endpoint untuk mendapatkan detail transaksi harian
     */
    public function getDailyTransactions($date)
    {
        try {
            // Validasi format tanggal
            $carbonDate = Carbon::parse($date);

            // Ambil transaksi penjualan
            $orders = Order::with('user')
                ->whereDate('created_at', $date)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'time' => $order->created_at->format('H:i'),
                        'user_name' => $order->user->name ?? '-',
                        'payment_method' => $order->payment_method,
                        'total_price' => $order->total_price,
                        'created_at' => $order->created_at->format('Y-m-d H:i:s')
                    ];
                });

            // Ambil transaksi pengeluaran
            $costs = Cost::with('user')
                ->whereDate('purchased_date', $date)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($cost) {
                    return [
                        'id' => $cost->id,
                        'item_name' => $cost->item_name,
                        'description' => $cost->description,
                        'quantity' => $cost->quantity,
                        'unit' => $cost->unit,
                        'unit_price' => $cost->unit_price,
                        'total_price' => $cost->total_price,
                        'user_name' => $cost->user->name ?? '-',
                        'purchased_date' => $cost->purchased_date->format('Y-m-d')
                    ];
                });

            // Hitung summary
            $summary = [
                'total_orders' => $orders->count(),
                'total_income' => $orders->sum('total_price'),
                'total_costs' => $costs->sum('total_price'),
                'net_profit' => $orders->sum('total_price') - $costs->sum('total_price')
            ];

            return response()->json([
                'success' => true,
                'date' => $date,
                'data' => [
                    'orders' => $orders,
                    'costs' => $costs,
                    'summary' => $summary
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}
