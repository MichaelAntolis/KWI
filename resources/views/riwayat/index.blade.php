@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white"><i class="bi bi-clock-history me-2"></i> Riwayat Transaksi</h5>
                @if($orders->total() == 0)
                <button type="button" class="btn btn-light btn-sm" onclick="confirmResetAutoIncrement()">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset ID Counter
                </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Filter Form -->
            <form class="mb-4" method="get" action="{{ route('riwayat.index') }}">
                <div class="row g-3">
                    <!-- Search Filter -->
                    <div class="col-md-4">
                        <label for="search" class="form-label">Cari Transaksi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="search"
                                placeholder="No Invoice atau Nama Kasir"
                                name="q" value="{{ $q }}">
                        </div>
                    </div>

                    <!-- Date Range Filter -->
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="{{ $startDate }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                            value="{{ $endDate }}">
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                            <a href="{{ route('riwayat.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Filter Info -->
            @if($q || $startDate || $endDate)
            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Filter Aktif:</strong>
                @if($q)
                Pencarian: "<strong>{{ $q }}</strong>"
                @endif
                @if($startDate && $endDate)
                @if($q), @endif
                Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}</strong>
                sampai <strong>{{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</strong>
                @elseif($startDate)
                @if($q), @endif
                Dari: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}</strong> hingga sekarang
                @elseif($endDate)
                @if($q), @endif
                Sampai: <strong>{{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</strong>
                @endif
                <a href="{{ route('riwayat.index') }}" class="btn btn-sm btn-outline-primary ms-2">
                    <i class="bi bi-x me-1"></i> Hapus Filter
                </a>
            </div>
            @endif

            <!-- Summary Info -->
            @if($orders->total() > 0)
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary bg-opacity-10 border-0">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-25 p-2 rounded me-3">
                                    <i class="bi bi-receipt text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $orders->total() }}</h6>
                                    <small class="text-muted">Total Transaksi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success bg-opacity-10 border-0">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-25 p-2 rounded me-3">
                                    <i class="bi bi-cash-stack text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Rp{{ number_format($orders->sum('total_price'),0,',','.') }}</h6>
                                    <small class="text-muted">Total Nilai</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info bg-opacity-10 border-0">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-25 p-2 rounded me-3">
                                    <i class="bi bi-calculator text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Rp{{ $orders->count() > 0 ? number_format($orders->sum('total_price')/$orders->count(),0,',','.') : 0 }}</h6>
                                    <small class="text-muted">Rata-rata per Transaksi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Transactions Table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#Invoice</th>
                            <th>Kasir</th>
                            <th>Tanggal & Waktu</th>
                            <th>Metode Pembayaran</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="order-card">
                            <td class="fw-semibold text-primary">#{{ $order->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary bg-opacity-10 p-2 rounded-circle me-2">
                                        <i class="bi bi-person-fill text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $order->user->name ?? '-' }}</div>
                                        <small class="text-muted">{{ $order->user->username ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $order->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $order->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($order->payment_method == 'cash') bg-success 
                                    @elseif($order->payment_method == 'qris') bg-primary
                                    @else bg-warning text-dark 
                                    @endif px-3 py-2">
                                    <i class="{{ $order->payment_method_icon }} me-1"></i>
                                    {{ $order->payment_method_label }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="fw-bold text-primary" style="font-size: 1.1rem;">
                                    Rp{{ number_format($order->total_price,0,',','.') }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('riwayat.show', $order->id) }}"
                                        class="btn btn-outline-primary" title="Lihat Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger"
                                        onclick="confirmDelete({{ $order->id }})" title="Hapus Invoice">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>

                                <!-- Hidden Delete Form -->
                                <form id="deleteForm{{ $order->id }}" action="{{ route('riwayat.destroy', $order->id) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://img.icons8.com/ios/50/000000/nothing-found.png" width="64" class="mb-3">
                                <h6 class="text-muted mb-2">Tidak ada transaksi ditemukan</h6>
                                @if($q || $startDate || $endDate)
                                <p class="text-muted mb-3">Coba ubah kriteria pencarian atau filter tanggal</p>
                                <a href="{{ route('riwayat.index') }}" class="btn btn-primary">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Tampilkan Semua
                                </a>
                                @else
                                <p class="text-muted">Belum ada transaksi yang tercatat</p>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }}
                    dari {{ $orders->total() }} transaksi
                </div>
                <div>
                    {{ $orders->appends(['q' => $q, 'start_date' => $startDate, 'end_date' => $endDate])->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="bi bi-trash3-fill text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center mb-3">
                    Apakah Anda yakin ingin menghapus <strong>Invoice <span id="invoiceNumber"></span></strong>?
                </p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Peringatan!</strong> Data yang sudah dihapus tidak dapat dikembalikan lagi.
                    Semua detail transaksi akan hilang permanen.
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="bi bi-trash-fill me-1"></i> Ya, Hapus Invoice
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .order-card {
        transition: all 0.2s ease;
    }

    .order-card:hover {
        background-color: rgba(78, 115, 223, 0.05);
        transform: translateX(5px);
    }

    .table th {
        font-weight: 600;
        color: #5a5c69;
        border-bottom: 2px solid #e3e6f0;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }

    .btn-group .btn {
        border-radius: 0.25rem;
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    let deleteModal;
    let currentOrderId;

    document.addEventListener('DOMContentLoaded', function() {
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

        const form = document.querySelector('form');
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const filterBtn = document.querySelector('button[type="submit"]');

        // Highlight tombol filter ketika tanggal berubah
        function highlightFilterButton() {
            filterBtn.classList.add('btn-warning');
            filterBtn.classList.remove('btn-primary');
            filterBtn.innerHTML = '<i class="bi bi-funnel me-1"></i> Terapkan Filter';
        }

        startDate.addEventListener('change', highlightFilterButton);
        endDate.addEventListener('change', highlightFilterButton);

        // Confirm delete button handler
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (currentOrderId) {
                document.getElementById('deleteForm' + currentOrderId).submit();
            }
        });
    });

    function confirmDelete(orderId) {
        currentOrderId = orderId;
        document.getElementById('invoiceNumber').textContent = '#' + orderId;
        deleteModal.show();
    }
</script>
@endpush
@endsection