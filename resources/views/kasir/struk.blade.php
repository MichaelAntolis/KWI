<!-- kasir/struk.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card card-custom animate-on-scroll" style="border-top: 4px solid #1cc88a;">
                <div class="card-body p-4 text-center">
                    <img src="{{ asset('images/dumplish.png') }}" width="60" height="60" alt="Logo" style="border-radius: 50%;">
                    <h4 class="fw-bold text-success mb-3">Struk Pembayaran</h4>

                    <div class="text-start mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Kasir:</span>
                            <span class="fw-semibold">{{ $order->user->name ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">No. Invoice:</span>
                            <span class="fw-semibold">#{{ $order->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Tanggal:</span>
                            <span class="fw-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Pembayaran:</span>
                            <span class="fw-semibold d-flex align-items-center">
                                <i class="{{ $order->payment_method_icon }} me-1"></i>
                                {{ $order->payment_method_label }}
                            </span>
                        </div>
                    </div>

                    <hr class="my-3">

                    @foreach($order->details as $detail)
                    <div class="text-start mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-semibold">{{ $detail->dumpling->name }}</span>
                            <span>x {{ $detail->quantity }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold mb-2">
                            <span>Subtotal:</span>
                            <span>Rp{{ number_format($detail->dumpling->price * $detail->quantity,0,',','.') }}</span>
                        </div>

                        <div class="ps-3">
                            <h6 class="text-muted small mb-2">Saus:</h6>
                            <ul class="list-unstyled mb-0">
                                @foreach($detail->sauces as $os)
                                <li class="d-flex justify-content-between mb-1">
                                    <span>
                                        {{ $os->sauce->name }}
                                        @if(!$os->is_free)
                                        <span class="badge bg-warning text-dark ms-1">+Rp2.000</span>
                                        @else
                                        <span class="badge bg-success ms-1">Free</span>
                                        @endif
                                    </span>
                                    @if(!$os->is_free)
                                    <span>Rp{{ number_format(2000,0,',','.') }}</span>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endforeach

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 fw-bold">Total:</h5>
                        <h3 class="mb-0 fw-bold text-primary">Rp{{ number_format($order->total_price,0,',','.') }}</h3>
                    </div>

                    <!-- Payment Status -->
                    <div class="alert alert-success mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Pembayaran berhasil diproses via {{ $order->payment_method_label }}. Terima kasih!
                    </div>

                    <!-- Payment Method Info -->
                    @if($order->payment_method == 'qris')
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-qr-code me-2"></i>
                        <strong>Pembayaran QRIS:</strong><br>
                        <small>Pembayaran telah dikonfirmasi melalui scan QR Code</small>
                    </div>
                    @elseif($order->payment_method == 'transfer')
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-bank me-2"></i>
                        <strong>Transfer Bank:</strong><br>
                        <small>Pembayaran telah dikonfirmasi melalui transfer bank</small>
                    </div>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('kasir.index') }}" class="btn btn-primary">
                            <i class="bi bi-cart-plus me-2"></i> Transaksi Baru
                        </a>
                        <button class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="bi bi-printer-fill me-2"></i> Cetak Struk
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
            border: none;
            box-shadow: none;
        }

        .no-print {
            display: none !important;
        }
    }
</style>
@endsection