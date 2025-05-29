<!-- kasir/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card card-custom animate-on-scroll">
                <div class="card-header">
                    <h5 class="mb-0 text-white"><i class="bi bi-cart3 me-2"></i> Transaksi Baru</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('kasir.store') }}" id="orderForm">
                        @csrf

                        <div class="mb-4">
                            <label for="dumpling" class="form-label fw-semibold">Pilih Dumpling</label>
                            <select name="dumpling_id" id="dumpling" class="form-select form-select-lg" required>
                                <option value="" selected disabled>-- Pilih Jenis Dumpling --</option>
                                @foreach($dumplings as $d)
                                <option value="{{ $d->id }}" data-price="{{ $d->price }}">
                                    {{ $d->name }} - Rp{{ number_format($d->price,0,',','.') }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="quantity" class="form-label fw-semibold">Jumlah Porsi</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" id="decrement">
                                    <i class="bi bi-dash-lg"></i>
                                </button>
                                <input type="number" min="1" name="quantity" id="quantity"
                                    class="form-control text-center" value="1" required>
                                <button type="button" class="btn btn-outline-secondary" id="increment">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Saus Gratis</label>
                            <div class="row g-2">
                                @foreach($sauces as $s)
                                <div class="col-6 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="free_sauce_id"
                                            id="sauce{{ $s->id }}" value="{{ $s->id }}" required>
                                        <label class="form-check-label d-flex align-items-center" for="sauce{{ $s->id }}">
                                            <span class="badge bg-success me-2">Free</span>
                                            {{ $s->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold d-flex justify-content-between align-items-center">
                                <span>Saus Tambahan <small class="text-muted">(Rp2.000/pcs)</small></span>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-sauce">
                                    <i class="bi bi-plus me-1"></i> Tambah
                                </button>
                            </label>
                            <div id="sauce-group-list" class="mb-3"></div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Metode Pembayaran</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            id="payment_cash" value="cash" required>
                                        <label class="form-check-label d-flex align-items-center" for="payment_cash">
                                            <div class="payment-option">
                                                <i class="bi bi-cash-stack text-success me-2" style="font-size: 1.5rem;"></i>
                                                <div>
                                                    <div class="fw-semibold">Tunai</div>
                                                    <small class="text-muted">Bayar dengan uang cash</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            id="payment_qris" value="qris" required>
                                        <label class="form-check-label d-flex align-items-center" for="payment_qris">
                                            <div class="payment-option">
                                                <i class="bi bi-qr-code text-primary me-2" style="font-size: 1.5rem;"></i>
                                                <div>
                                                    <div class="fw-semibold">QRIS</div>
                                                    <small class="text-muted">Scan QR untuk bayar</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            id="payment_transfer" value="transfer" required>
                                        <label class="form-check-label d-flex align-items-center" for="payment_transfer">
                                            <div class="payment-option">
                                                <i class="bi bi-bank text-warning me-2" style="font-size: 1.5rem;"></i>
                                                <div>
                                                    <div class="fw-semibold">Transfer</div>
                                                    <small class="text-muted">Transfer bank</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-light p-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-semibold">Total Pembayaran</h6>
                                <h4 class="mb-0 fw-bold text-primary" id="totalPrice">Rp0</h4>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="bi bi-printer-fill me-2"></i> Proses & Cetak Struk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .payment-option {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border: 2px solid #e9ecef;
        border-radius: 0.5rem;
        transition: all 0.2s;
        width: 100%;
    }

    .form-check-input:checked+.form-check-label .payment-option {
        border-color: #4e73df;
        background-color: rgba(78, 115, 223, 0.1);
    }

    .payment-option:hover {
        border-color: #4e73df;
        background-color: rgba(78, 115, 223, 0.05);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity controls
        const quantityInput = document.getElementById('quantity');
        const decrementBtn = document.getElementById('decrement');
        const incrementBtn = document.getElementById('increment');

        decrementBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                quantityInput.value = value - 1;
                calculateTotal();
            }
        });

        incrementBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            quantityInput.value = value + 1;
            calculateTotal();
        });

        quantityInput.addEventListener('change', calculateTotal);

        // Sauce management
        const addBtn = document.getElementById('add-sauce');
        const groupList = document.getElementById('sauce-group-list');
        let index = 0;

        addBtn.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'row g-2 mb-2 sauce-row';

            row.innerHTML = `
            <div class="col-5">
                <select name="extra_sauces[${index}][id]" class="form-select" required>
                    <option value="" selected disabled>-- Pilih Saus --</option>
                    @foreach($sauces as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <input type="number" min="1" name="extra_sauces[${index}][qty]" 
                       class="form-control" placeholder="Jumlah" value="1" required>
            </div>
            <div class="col-3 d-flex">
                <button type="button" class="btn btn-outline-danger w-100 remove-sauce">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </div>
        `;

            groupList.appendChild(row);
            index++;

            // Add event listeners to new inputs
            row.querySelector('input').addEventListener('change', calculateTotal);
            row.querySelector('select').addEventListener('change', calculateTotal);
        });

        groupList.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-sauce') ||
                e.target.closest('.remove-sauce')) {
                e.target.closest('.sauce-row').remove();
                calculateTotal();
            }
        });

        // Price calculation
        function calculateTotal() {
            const dumplingSelect = document.getElementById('dumpling');
            const quantity = parseInt(quantityInput.value) || 0;
            const dumplingPrice = dumplingSelect.selectedOptions[0]?.dataset.price || 0;

            // Calculate extra sauces
            let extraSauceQty = 0;
            document.querySelectorAll('[name^="extra_sauces"]').forEach(input => {
                if (input.name.includes('[qty]')) {
                    extraSauceQty += parseInt(input.value) || 0;
                }
            });

            const basePrice = dumplingPrice * quantity;
            const extraSaucePrice = extraSauceQty * 2000 * quantity;
            const totalPrice = basePrice + extraSaucePrice;

            document.getElementById('totalPrice').textContent =
                `Rp${totalPrice.toLocaleString('id-ID')}`;
        }

        // Initial calculation and event listeners
        document.getElementById('dumpling').addEventListener('change', calculateTotal);
        calculateTotal();
    });
</script>
@endpush
@endsection