@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-custom mb-4">
        <div class="card-header">
            <h5 class="mb-0 text-white"><i class="bi bi-bar-chart me-2"></i> Laporan Penjualan & Pengeluaran</h5>
        </div>
        <div class="card-body">
            <!-- Statistik Utama -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card h-100 dumpling-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-muted mb-2">Total Transaksi</h6>
                                    <h3 class="mb-0 fw-bold">{{ $totalOrder ?? 0 }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-receipt text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 sauce-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-muted mb-2">Total Pendapatan</h6>
                                    <h3 class="mb-0 fw-bold text-success">Rp{{ number_format($totalIncome ?? 0,0,',','.') }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-cash-stack text-success" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-muted mb-2">Total Pengeluaran</h6>
                                    <h3 class="mb-0 fw-bold text-danger">Rp{{ number_format($totalCost ?? 0,0,',','.') }}</h3>
                                </div>
                                <div class="bg-danger bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-cart-dash text-danger" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-muted mb-2">Profit/Keuntungan</h6>
                                    <h3 class="mb-0 fw-bold {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp{{ number_format($totalProfit ?? 0,0,',','.') }}
                                    </h3>
                                </div>
                                <div class="bg-{{ $totalProfit >= 0 ? 'success' : 'danger' }} bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-{{ $totalProfit >= 0 ? 'graph-up-arrow' : 'graph-down-arrow' }} text-{{ $totalProfit >= 0 ? 'success' : 'danger' }}" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profit Margin Info -->
            @if($totalIncome > 0)
            <div class="alert alert-{{ $totalProfit >= 0 ? 'success' : 'warning' }} mb-4">
                <i class="bi bi-{{ $totalProfit >= 0 ? 'check-circle' : 'exclamation-triangle' }}-fill me-2"></i>
                <strong>Margin Keuntungan:</strong>
                {{ number_format(($totalProfit / $totalIncome) * 100, 1) }}%
                @if($totalProfit >= 0)
                - Bisnis Anda menguntungkan! 🎉
                @else
                - Pengeluaran melebihi pendapatan, perlu evaluasi biaya.
                @endif
            </div>
            @endif

            <!-- Pengeluaran per User -->
            @if($costPerUser->count() > 0)
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold mb-3">
                                <i class="bi bi-person-lines-fill me-2"></i>Pengeluaran per User
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th class="text-end">Total Pengeluaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($costPerUser as $userCost)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-danger bg-opacity-10 p-1 rounded-circle me-2">
                                                        <i class="bi bi-person-fill text-danger"></i>
                                                    </div>
                                                    {{ $userCost->name }}
                                                </div>
                                            </td>
                                            <td class="text-end fw-semibold text-danger">
                                                Rp{{ number_format($userCost->total_cost, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold mb-3">
                                <i class="bi bi-trophy me-2"></i>Top 5 Pengeluaran Terbesar
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Barang</th>
                                            <th class="text-end">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topCosts as $cost)
                                        <tr>
                                            <td>
                                                <div>
                                                    <div class="fw-semibold">{{ Str::limit($cost->item_name, 25) }}</div>
                                                    <small class="text-muted">{{ $cost->user->name }} - {{ $cost->purchased_date->format('d/m/Y') }}</small>
                                                </div>
                                            </td>
                                            <td class="text-end fw-semibold text-danger">
                                                Rp{{ number_format($cost->total_price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Belum ada data pengeluaran</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Produk Terlaris -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold mb-3">Produk Terlaris</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-danger bg-opacity-10 p-2 rounded me-3">
                                    <i class="bi bi-egg-fried text-danger"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $bestDumpling->dumpling_name ?? 'Tidak ada data' }}</h6>
                                    <small class="text-muted">{{ $bestDumpling->total_sold ?? 0 }} porsi</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                    <i class="bi bi-droplet text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $bestSauce->sauce_name ?? 'Tidak ada data' }}</h6>
                                    <small class="text-muted">{{ $bestSauce->total_sold ?? 0 }} pcs</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold mb-3">Metode Pembayaran Terfavorit</h6>
                            @php
                            $mostUsedPayment = collect($paymentMethods)->sortDesc()->keys()->first();
                            $mostUsedCount = collect($paymentMethods)->sortDesc()->first();
                            @endphp
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                    @if($mostUsedPayment == 'Tunai')
                                    <i class="bi bi-cash-stack text-info"></i>
                                    @elseif($mostUsedPayment == 'QRIS')
                                    <i class="bi bi-qr-code text-info"></i>
                                    @else
                                    <i class="bi bi-bank text-info"></i>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $mostUsedPayment ?? 'Tidak ada data' }}</h6>
                                    <small class="text-muted">{{ $mostUsedCount ?? 0 }} transaksi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Pembayaran -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold mb-3">
                                <i class="bi bi-credit-card me-2"></i>Informasi Pembayaran
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-cash-stack text-success me-3" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">Tunai</h6>
                                                <small class="text-muted">{{ $paymentMethods['Tunai'] ?? 0 }} transaksi</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold">Rp{{ number_format($paymentIncome['Tunai'] ?? 0,0,',','.') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-qr-code text-primary me-3" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">QRIS</h6>
                                                <small class="text-muted">{{ $paymentMethods['QRIS'] ?? 0 }} transaksi</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold">Rp{{ number_format($paymentIncome['QRIS'] ?? 0,0,',','.') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-bank text-warning me-3" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">Transfer Bank</h6>
                                                <small class="text-muted">{{ $paymentMethods['Transfer Bank'] ?? 0 }} transaksi</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold">Rp{{ number_format($paymentIncome['Transfer Bank'] ?? 0,0,',','.') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold mb-3">Total Item Sold</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Total Terjual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($totalItemsSold as $item)
                                        <tr>
                                            <td>{{ $item->dumpling_name ?? $item->sauce_name }}</td>
                                            <td>{{ $item->total_sold }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold mb-3">
                                <i class="bi bi-calendar-date me-2"></i>Laporan Harian (Pendapatan vs Pengeluaran)
                            </h6>

                            <!-- Filter Tanggal -->
                            <form method="GET" action="{{ route('laporan.index') }}" class="mb-4">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date"
                                            value="{{ $startDate }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date"
                                            value="{{ $endDate }}">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-funnel me-1"></i> Filter
                                            </button>
                                            <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary">
                                                <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Tabel Laporan Harian -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th class="text-center">Transaksi</th>
                                            <th class="text-end">Pendapatan</th>
                                            <th class="text-end">Pengeluaran</th>
                                            <th class="text-end">Profit</th>
                                            <th class="text-center">Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dailyReport as $report)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($report->date)->format('d F Y') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($report->date)->format('l') }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                    {{ $report->total_order }} transaksi
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="fw-bold text-success">Rp{{ number_format($report->total_income,0,',','.') }}</div>
                                            </td>
                                            <td class="text-end">
                                                <div class="fw-bold text-danger">Rp{{ number_format($report->total_cost,0,',','.') }}</div>
                                            </td>
                                            <td class="text-end">
                                                <div class="fw-bold {{ $report->profit >= 0 ? 'text-success' : 'text-danger' }}">
                                                    Rp{{ number_format($report->profit,0,',','.') }}
                                                    @if($report->total_income > 0)
                                                    <br><small class="text-muted">({{ number_format(($report->profit / $report->total_income) * 100, 1) }}%)</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-info"
                                                    onclick="showTransactionDetail('{{ $report->date }}')"
                                                    title="Lihat Detail Transaksi">
                                                    <i class="bi bi-eye-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <img src="https://img.icons8.com/ios/50/000000/nothing-found.png" width="48">
                                                <p class="mt-2 mb-0 text-muted">Tidak ada data ditemukan</p>
                                                @if($startDate || $endDate)
                                                <small class="text-muted">Coba ubah filter tanggal</small>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    @if($dailyReport->count() > 0)
                                    <tfoot class="table-light">
                                        <tr>
                                            <th>Total</th>
                                            <th class="text-center">
                                                <span class="badge bg-primary">{{ $dailyReport->sum('total_order') }} transaksi</span>
                                            </th>
                                            <th class="text-end fw-bold text-success">
                                                Rp{{ number_format($dailyReport->sum('total_income'),0,',','.') }}
                                            </th>
                                            <th class="text-end fw-bold text-danger">
                                                Rp{{ number_format($dailyReport->sum('total_cost'),0,',','.') }}
                                            </th>
                                            <th class="text-end fw-bold {{ $dailyReport->sum('profit') >= 0 ? 'text-success' : 'text-danger' }}">
                                                Rp{{ number_format($dailyReport->sum('profit'),0,',','.') }}
                                            </th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>

                            <!-- Info Filter -->
                            @if($startDate || $endDate)
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Filter Aktif:</strong>
                                @if($startDate && $endDate)
                                Menampilkan data dari {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}
                                sampai {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
                                @elseif($startDate)
                                Menampilkan data dari {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} hingga sekarang
                                @elseif($endDate)
                                Menampilkan data sampai {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Transaksi Harian -->
<div class="modal fade" id="transactionDetailModal" tabindex="-1" aria-labelledby="transactionDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="transactionDetailModalLabel">
                    <i class="bi bi-calendar-date me-2"></i>Detail Transaksi Harian
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="transactionDetailContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Memuat data transaksi...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let transactionModal;

    document.addEventListener('DOMContentLoaded', function() {
        transactionModal = new bootstrap.Modal(document.getElementById('transactionDetailModal'));
    });

    function showTransactionDetail(date) {
        // Reset modal content
        document.getElementById('transactionDetailContent').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Memuat data transaksi...</p>
            </div>
        `;

        // Update modal title
        const formattedDate = new Date(date).toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('transactionDetailModalLabel').innerHTML =
            `<i class="bi bi-calendar-date me-2"></i>Detail Transaksi - ${formattedDate}`;

        // Show modal
        transactionModal.show();

        // Fetch transaction data
        fetch(`/api/daily-transactions/${date}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderTransactionDetail(data.data, date);
                } else {
                    showError('Gagal memuat data transaksi');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Terjadi kesalahan saat memuat data');
            });
    }

    function renderTransactionDetail(data, date) {
        const {
            orders,
            costs,
            summary
        } = data;

        let content = `
            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary bg-opacity-10 border-0">
                        <div class="card-body text-center py-3">
                            <h5 class="fw-bold text-primary mb-1">${summary.total_orders}</h5>
                            <small class="text-muted">Total Transaksi</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success bg-opacity-10 border-0">
                        <div class="card-body text-center py-3">
                            <h5 class="fw-bold text-success mb-1">Rp${numberFormat(summary.total_income)}</h5>
                            <small class="text-muted">Total Pendapatan</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger bg-opacity-10 border-0">
                        <div class="card-body text-center py-3">
                            <h5 class="fw-bold text-danger mb-1">Rp${numberFormat(summary.total_costs)}</h5>
                            <small class="text-muted">Total Pengeluaran</small>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Transaksi Penjualan
        if (orders.length > 0) {
            content += `
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-receipt text-success me-2"></i>Transaksi Penjualan (${orders.length})
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice</th>
                                    <th>Waktu</th>
                                    <th>Kasir</th>
                                    <th>Pembayaran</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            orders.forEach(order => {
                const paymentIcon = getPaymentIcon(order.payment_method);
                const paymentLabel = getPaymentLabel(order.payment_method);

                content += `
                    <tr>
                        <td><span class="fw-semibold text-primary">#${order.id}</span></td>
                        <td><small>${order.time}</small></td>
                        <td><small>${order.user_name}</small></td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info px-2 py-1">
                                <i class="${paymentIcon} me-1"></i>${paymentLabel}
                            </span>
                        </td>
                        <td class="text-end fw-semibold">Rp${numberFormat(order.total_price)}</td>
                        <td class="text-center">
                            <a href="/riwayat/${order.id}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        </td>
                    </tr>
                `;
            });

            content += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }

        // Transaksi Pengeluaran
        if (costs.length > 0) {
            content += `
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-cart-dash text-danger me-2"></i>Pengeluaran (${costs.length})
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>User</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            costs.forEach(cost => {
                content += `
                    <tr>
                        <td>
                            <div class="fw-semibold">${cost.item_name}</div>
                            ${cost.description ? `<small class="text-muted">${cost.description}</small>` : ''}
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info px-2 py-1">
                                ${cost.quantity} ${cost.unit}
                            </span>
                        </td>
                        <td><small>${cost.user_name}</small></td>
                        <td class="text-end fw-semibold text-danger">Rp${numberFormat(cost.total_price)}</td>
                    </tr>
                `;
            });

            content += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }

        // Jika tidak ada data
        if (orders.length === 0 && costs.length === 0) {
            content += `
                <div class="text-center py-5">
                    <img src="https://img.icons8.com/ios/50/000000/nothing-found.png" width="64" class="mb-3">
                    <h6 class="text-muted mb-2">Tidak ada transaksi pada tanggal ini</h6>
                    <p class="text-muted">Belum ada aktivitas penjualan atau pengeluaran</p>
                </div>
            `;
        }

        document.getElementById('transactionDetailContent').innerHTML = content;
    }

    function showError(message) {
        document.getElementById('transactionDetailContent').innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                <h6 class="text-muted mt-3">${message}</h6>
                <button class="btn btn-primary mt-2" onclick="transactionModal.hide()">Tutup</button>
            </div>
        `;
    }

    function numberFormat(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function getPaymentIcon(method) {
        const icons = {
            'cash': 'bi-cash-stack',
            'qris': 'bi-qr-code',
            'transfer': 'bi-bank'
        };
        return icons[method] || 'bi-cash-stack';
    }

    function getPaymentLabel(method) {
        const labels = {
            'cash': 'Tunai',
            'qris': 'QRIS',
            'transfer': 'Transfer'
        };
        return labels[method] || 'Tunai';
    }
</script>
@endpush
@endsection