@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white"><i class="bi bi-cart-dash me-2"></i> Manajemen Pengeluaran</h5>
                <a href="{{ route('costs.create') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Pengeluaran
                </a>
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
            <form class="mb-4" method="get" action="{{ route('costs.index') }}">
                <div class="row g-3">
                    <!-- Search Filter -->
                    <div class="col-md-3">
                        <label for="search" class="form-label">Cari Barang</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="search"
                                placeholder="Nama barang atau keterangan"
                                name="q" value="{{ $q }}">
                        </div>
                    </div>

                    <!-- User Filter -->
                    <div class="col-md-2">
                        <label for="user_filter" class="form-label">Filter User</label>
                        <select class="form-select" id="user_filter" name="user_id">
                            <option value="">Semua User</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range Filter -->
                    <div class="col-md-2">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="{{ $startDate }}">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                            value="{{ $endDate }}">
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                            <a href="{{ route('costs.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Filter Info -->
            @if($q || $startDate || $endDate || $userId)
            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Filter Aktif:</strong>
                @if($q)
                Pencarian: "<strong>{{ $q }}</strong>"
                @endif
                @if($userId)
                @if($q), @endif
                User: <strong>{{ $users->find($userId)->name ?? 'Unknown' }}</strong>
                @endif
                @if($startDate && $endDate)
                @if($q || $userId), @endif
                Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}</strong>
                sampai <strong>{{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</strong>
                @elseif($startDate)
                @if($q || $userId), @endif
                Dari: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}</strong> hingga sekarang
                @elseif($endDate)
                @if($q || $userId), @endif
                Sampai: <strong>{{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</strong>
                @endif
                <a href="{{ route('costs.index') }}" class="btn btn-sm btn-outline-primary ms-2">
                    <i class="bi bi-x me-1"></i> Hapus Filter
                </a>
            </div>
            @endif

            <!-- Summary Info -->
            @if($costs->total() > 0)
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card bg-danger bg-opacity-10 border-0">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger bg-opacity-25 p-2 rounded me-3">
                                    <i class="bi bi-cart-dash text-danger"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $costs->total() }}</h6>
                                    <small class="text-muted">Total Item</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning bg-opacity-10 border-0">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-25 p-2 rounded me-3">
                                    <i class="bi bi-cash-stack text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Rp{{ number_format($totalCost,0,',','.') }}</h6>
                                    <small class="text-muted">Total Pengeluaran</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info bg-opacity-10 border-0">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-25 p-2 rounded me-3">
                                    <i class="bi bi-calculator text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Rp{{ $costs->count() > 0 ? number_format($totalCost/$costs->count(),0,',','.') : 0 }}</h6>
                                    <small class="text-muted">Rata-rata per Item</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary bg-opacity-10 border-0">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary bg-opacity-25 p-2 rounded me-3">
                                    <i class="bi bi-people text-secondary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $costs->pluck('user_id')->unique()->count() }}</h6>
                                    <small class="text-muted">User Aktif</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Costs Table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Quantity</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Total Harga</th>
                            <th>Dibeli Oleh</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($costs as $cost)
                        <tr class="cost-row">
                            <td>
                                <div class="fw-semibold">{{ $cost->purchased_date->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $cost->purchased_date->format('l') }}</small>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ $cost->item_name }}</div>
                                    @if($cost->description)
                                    <small class="text-muted">{{ Str::limit($cost->description, 50) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info px-2 py-1">
                                    {{ $cost->quantity_with_unit }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="fw-semibold">Rp{{ number_format($cost->unit_price,0,',','.') }}</div>
                                <small class="text-muted">per {{ $cost->unit }}</small>
                            </td>
                            <td class="text-end">
                                <div class="fw-bold text-danger" style="font-size: 1.1rem;">
                                    Rp{{ number_format($cost->total_price,0,',','.') }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-2">
                                        <i class="bi bi-person-fill text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $cost->user->name }}</div>
                                        <small class="text-muted">{{ $cost->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    @if($cost->user_id == auth()->id())
                                    <a href="{{ route('costs.edit', $cost->id) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" onclick="confirmDeleteCost({{ $cost->id }})" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @else
                                    <small class="text-muted">Tidak dapat diedit</small>
                                    @endif
                                </div>

                                @if($cost->user_id == auth()->id())
                                <!-- Hidden Delete Form -->
                                <form id="deleteCostForm{{ $cost->id }}" action="{{ route('costs.destroy', $cost->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <img src="https://img.icons8.com/ios/50/000000/nothing-found.png" width="64" class="mb-3">
                                <h6 class="text-muted mb-2">Tidak ada data pengeluaran ditemukan</h6>
                                @if($q || $startDate || $endDate || $userId)
                                <p class="text-muted mb-3">Coba ubah kriteria filter</p>
                                <a href="{{ route('costs.index') }}" class="btn btn-primary">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Tampilkan Semua
                                </a>
                                @else
                                <p class="text-muted mb-3">Belum ada pengeluaran yang tercatat</p>
                                <a href="{{ route('costs.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Pengeluaran Pertama
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($costs->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $costs->firstItem() ?? 0 }} - {{ $costs->lastItem() ?? 0 }}
                    dari {{ $costs->total() }} data
                </div>
                <div>
                    {{ $costs->appends(['q' => $q, 'user_id' => $userId, 'start_date' => $startDate, 'end_date' => $endDate])->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCostModal" tabindex="-1" aria-labelledby="deleteCostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger" id="deleteCostModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="bi bi-trash3-fill text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center mb-3">
                    Apakah Anda yakin ingin menghapus data pengeluaran ini?
                </p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Peringatan!</strong> Data yang sudah dihapus tidak dapat dikembalikan lagi.
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCostBtn">
                    <i class="bi bi-trash-fill me-1"></i> Ya, Hapus Data
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .cost-row {
        transition: all 0.2s ease;
    }

    .cost-row:hover {
        background-color: rgba(220, 53, 69, 0.05);
        transform: translateX(3px);
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
    let deleteCostModal;
    let currentCostId;

    document.addEventListener('DOMContentLoaded', function() {
        deleteCostModal = new bootstrap.Modal(document.getElementById('deleteCostModal'));

        // Confirm delete button handler
        document.getElementById('confirmDeleteCostBtn').addEventListener('click', function() {
            if (currentCostId) {
                document.getElementById('deleteCostForm' + currentCostId).submit();
            }
        });
    });

    function confirmDeleteCost(costId) {
        currentCostId = costId;
        deleteCostModal.show();
    }
</script>
@endpush
@endsection