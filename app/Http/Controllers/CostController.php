<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cost;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CostController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $userId = $request->input('user_id');

        $costs = Cost::with('user')
            ->when($q, function ($query) use ($q) {
                $query->where('item_name', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%");
            })
            ->when($startDate || $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereDateBetween($startDate, $endDate);
            })
            ->when($userId, function ($query) use ($userId) {
                $query->byUser($userId);
            })
            ->orderBy('purchased_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get users for filter dropdown
        $users = User::orderBy('name')->get();

        // Calculate totals for filtered results
        $totalCost = Cost::when($q, function ($query) use ($q) {
            $query->where('item_name', 'like', "%$q%")
                ->orWhere('description', 'like', "%$q%");
        })
            ->when($startDate || $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereDateBetween($startDate, $endDate);
            })
            ->when($userId, function ($query) use ($userId) {
                $query->byUser($userId);
            })
            ->sum('total_price');

        return view('costs.index', compact('costs', 'users', 'q', 'startDate', 'endDate', 'userId', 'totalCost'));
    }

    public function create()
    {
        return view('costs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:20',
            'unit_price' => 'required|integer|min:1',
            'purchased_date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ]);

        $totalPrice = $request->quantity * $request->unit_price;

        Cost::create([
            'user_id' => auth()->id(),
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'unit_price' => $request->unit_price,
            'total_price' => $totalPrice,
            'description' => $request->description,
            'purchased_date' => $request->purchased_date,
        ]);

        return redirect()->route('costs.index')->with('success', 'Data pengeluaran berhasil ditambahkan!');
    }

    public function edit(Cost $cost)
    {
        // Pastikan hanya user yang menginput yang bisa edit, atau admin
        if ($cost->user_id !== auth()->id()) {
            return redirect()->route('costs.index')->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        return view('costs.edit', compact('cost'));
    }

    public function update(Request $request, Cost $cost)
    {
        // Pastikan hanya user yang menginput yang bisa edit
        if ($cost->user_id !== auth()->id()) {
            return redirect()->route('costs.index')->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:20',
            'unit_price' => 'required|integer|min:1',
            'purchased_date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ]);

        $totalPrice = $request->quantity * $request->unit_price;

        $cost->update([
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'unit_price' => $request->unit_price,
            'total_price' => $totalPrice,
            'description' => $request->description,
            'purchased_date' => $request->purchased_date,
        ]);

        return redirect()->route('costs.index')->with('success', 'Data pengeluaran berhasil diperbarui!');
    }

    public function destroy(Cost $cost)
    {
        // Pastikan hanya user yang menginput yang bisa hapus
        if ($cost->user_id !== auth()->id()) {
            return redirect()->route('costs.index')->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        $cost->delete();
        return redirect()->route('costs.index')->with('success', 'Data pengeluaran berhasil dihapus!');
    }
}
