<!-- riwayat/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Invoice Card -->
            <div class="card card-custom animate-on-scroll border-0 shadow-lg" style="border-top: 4px solid #4e73df;">
                <!-- Invoice Header -->
                <div class="card-header bg-white py-4 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0 fw-bold text-primary">INVOICE</h2>
                            <p class="text-muted mb-0">#{{ $order->id }}</p>
                        </div>
                        <div class="text-end">
                            <img src="{{ asset('images/dumplish.png') }}" width="60" height="60" alt="Logo" style="border-radius: 50%;">
                        </div>
                    </div>
                </div>

                <!-- Invoice Body -->
                <div class="card-body p-4">
                    <!-- Order Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-uppercase text-muted">Info Kasir</h6>
                            <p class="mb-1">{{ $order->user->name ?? '-' }}</p>
                            <p class="small text-muted mb-0">{{ $order->created_at->format('d F Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="fw-bold text-uppercase text-muted">Status Pembayaran</h6>
                            <span class="badge bg-success bg-opacity-10 text-success py-2 px-3 mb-2">
                                <i class="bi bi-check-circle-fill me-1"></i> Lunas
                            </span>
                            <br>
                            <span class="badge bg-info bg-opacity-10 text-info py-2 px-3">
                                <i class="{{ $order->payment_method_icon }} me-1"></i>
                                {{ $order->payment_method_label }}
                            </span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-semibold">Item</th>
                                    <th class="fw-semibold text-end">Harga</th>
                                    <th class="fw-semibold text-end">Qty</th>
                                    <th class="fw-semibold text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->details as $detail)
                                <tr>
                                    <td>
                                        <h6 class="mb-1 fw-semibold">{{ $detail->dumpling->name }}</h6>
                                        <ul class="list-unstyled small text-muted">
                                            @foreach($detail->sauces as $os)
                                            <li>
                                                {{ $os->sauce->name }}
                                                @if(!$os->is_free)
                                                <span class="badge bg-warning text-dark ms-1">+Rp2.000</span>
                                                @else
                                                <span class="badge bg-success ms-1">Free</span>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-end">Rp{{ number_format($detail->dumpling->price,0,',','.') }}</td>
                                    <td class="text-end">{{ $detail->quantity }}</td>
                                    <td class="text-end fw-semibold">Rp{{ number_format($detail->dumpling->price * $detail->quantity,0,',','.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Extra Charges -->
                    @php
                    $extraSauceTotal = $order->total_price - ($order->details->sum(function($detail) {
                    return $detail->dumpling->price * $detail->quantity;
                    }));
                    @endphp
                    @if($extraSauceTotal > 0)
                    <div class="bg-light p-3 rounded mb-4">
                        <h6 class="fw-semibold mb-3">Biaya Tambahan</h6>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Saus Tambahan</span>
                            <span class="fw-semibold">+ Rp{{ number_format($extraSauceTotal,0,',','.') }}</span>
                        </div>
                    </div>
                    @endif

                    <!-- Payment Method Details -->
                    <div class="bg-light p-3 rounded mb-4">
                        <h6 class="fw-semibold mb-3">Detail Pembayaran</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="{{ $order->payment_method_icon }} text-primary me-2" style="font-size: 1.2rem;"></i>
                                <span>{{ $order->payment_method_label }}</span>
                            </div>
                            <span class="text-success fw-semibold">
                                <i class="bi bi-check-circle-fill me-1"></i> Lunas
                            </span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="bg-primary bg-opacity-10 p-4 rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">Total Pembayaran</h5>
                            <h3 class="mb-0 fw-bold text-primary">Rp{{ number_format($order->total_price,0,',','.') }}</h3>
                        </div>
                    </div>

                    <!-- Payment Method Info -->
                    <div class="mt-3">
                        @if($order->payment_method == 'cash')
                        <p class="small text-muted mb-1">
                            <i class="bi bi-cash-stack me-1"></i> Pembayaran dilakukan secara tunai
                        </p>
                        @elseif($order->payment_method == 'qris')
                        <p class="small text-muted mb-1">
                            <i class="bi bi-qr-code me-1"></i> Pembayaran dilakukan melalui QRIS
                        </p>
                        @elseif($order->payment_method == 'transfer')
                        <p class="small text-muted mb-1">
                            <i class="bi bi-bank me-1"></i> Pembayaran dilakukan melalui transfer bank
                        </p>
                        @endif
                        <p class="small text-muted">
                            <i class="bi bi-clock me-1"></i> Invoice ini valid hingga {{ $order->created_at->addDays(7)->format('d F Y') }}
                        </p>
                    </div>
                </div>

                <!-- Invoice Footer -->
                <div class="card-footer bg-white py-3 border-0">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                        <button class="btn btn-outline-secondary mb-2 mb-sm-0" onclick="window.print()">
                            <i class="bi bi-printer-fill me-1"></i> Cetak Invoice
                        </button>
                        <div class="d-flex gap-2">
                            <a href="{{ route('riwayat.index') }}" class="btn btn-light">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                <i class="bi bi-trash-fill me-1"></i> Hapus Invoice
                            </button>
                            <a href="{{ route('kasir.index') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Transaksi Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus Invoice
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="bi bi-trash3-fill text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center mb-3">
                    Apakah Anda yakin ingin menghapus <strong>Invoice #{{ $order->id }}</strong>?
                </p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Peringatan!</strong> Data yang sudah dihapus tidak dapat dikembalikan lagi.
                    Semua detail transaksi akan hilang permanen dan akan mempengaruhi laporan.
                </div>
                <div class="bg-light p-3 rounded mb-3">
                    <h6 class="fw-semibold mb-2">Detail yang akan dihapus:</h6>
                    <ul class="list-unstyled mb-0">
                        <li><i class="bi bi-check text-danger me-2"></i>Invoice #{{ $order->id }}</li>
                        <li><i class="bi bi-check text-danger me-2"></i>Total: Rp{{ number_format($order->total_price,0,',','.') }}</li>
                        <li><i class="bi bi-check text-danger me-2"></i>{{ $order->details->count() }} item detail</li>
                        <li><i class="bi bi-check text-danger me-2"></i>Pembayaran: {{ $order->payment_method_label }}</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-danger" onclick="submitDelete()">
                    <i class="bi bi-trash-fill me-1"></i> Ya, Hapus Invoice
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteForm" action="{{ route('riwayat.destroy', $order->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Print Styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .card,
        .card * {
            visibility: visible;
        }

        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            border: none;
            box-shadow: none;
        }

        .no-print,
        .card-footer {
            display: none !important;
        }

        @page {
            size: auto;
            margin: 5mm;
        }
    }
</style>

@push('scripts')
<script>
    let deleteModal;

    document.addEventListener('DOMContentLoaded', function() {
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    });

    function confirmDelete() {
        deleteModal.show();
    }

    function submitDelete() {
        document.getElementById('deleteForm').submit();
    }
</script>
@endpush
@endsection