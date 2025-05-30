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

    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            // Hapus semua relasi terkait
            foreach ($order->details as $detail) {
                // Hapus order sauces
                $detail->sauces()->delete();
            }

            // Hapus order details
            $order->details()->delete();

            // Hapus order
            $order->delete();

            // Reset auto-increment jika tidak ada data tersisa
            $this->resetAutoIncrementIfEmpty();

            DB::commit();

            return redirect()->route('riwayat.index')->with('success', 'Invoice #' . $order->id . ' berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('riwayat.index')->with('error', 'Gagal menghapus invoice: ' . $e->getMessage());
        }
    }

    /**
     * Reset auto-increment jika tabel kosong
     */
    private function resetAutoIncrementIfEmpty()
    {
        // Cek apakah tabel orders kosong
        $orderCount = Order::count();

        if ($orderCount == 0) {
            // Reset auto-increment untuk semua tabel terkait
            DB::statement('ALTER TABLE orders AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE order_details AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE order_sauces AUTO_INCREMENT = 1');
        }
    }

    /**
     * Method untuk reset manual auto-increment (bisa dipanggil via route)
     */
    public function resetAutoIncrement()
    {
        try {
            DB::beginTransaction();

            // Reset auto-increment untuk semua tabel terkait
            DB::statement('ALTER TABLE orders AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE order_details AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE order_sauces AUTO_INCREMENT = 1');

            DB::commit();

            return redirect()->route('riwayat.index')->with('success', 'Auto-increment berhasil direset! Invoice selanjutnya akan dimulai dari #1');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('riwayat.index')->with('error', 'Gagal reset auto-increment: ' . $e->getMessage());
        }
    }
}
