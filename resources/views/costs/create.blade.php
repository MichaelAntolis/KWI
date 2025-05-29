<!-- costs/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom animate-on-scroll">
                <div class="card-header">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-plus-circle me-2"></i> Tambah Pengeluaran Baru
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('costs.store') }}" id="costForm">
                        @csrf

                        <!-- Informasi User -->
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Dicatat oleh:</strong> {{ auth()->user()->name }}
                            <small class="text-muted">pada {{ now()->format('d F Y, H:i') }}</small>
                        </div>

                        <div class="row g-3">
                            <!-- Nama Barang -->
                            <div class="col-md-6">
                                <label for="item_name" class="form-label fw-semibold">Nama Barang/Bahan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('item_name') is-invalid @enderror"
                                    id="item_name" name="item_name"
                                    value="{{ old('item_name') }}"
                                    placeholder="Contoh: Tepung Terigu, Daging Ayam, dll" required>
                                @error('item_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Pembelian -->
                            <div class="col-md-6">
                                <label for="purchased_date" class="form-label fw-semibold">Tanggal Pembelian <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('purchased_date') is-invalid @enderror"
                                    id="purchased_date" name="purchased_date"
                                    value="{{ old('purchased_date', date('Y-m-d')) }}" required>
                                @error('purchased_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div class="col-md-4">
                                <label for="quantity" class="form-label fw-semibold">Jumlah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary" id="decrementQty">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" step="0.01" min="0.01"
                                        class="form-control text-center @error('quantity') is-invalid @enderror"
                                        id="quantity" name="quantity"
                                        value="{{ old('quantity', 1) }}" required>
                                    <button type="button" class="btn btn-outline-secondary" id="incrementQty">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                                @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Unit -->
                            <div class="col-md-4">
                                <label for="unit" class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                                <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit" required>
                                    <option value="" disabled>Pilih Satuan</option>
                                    <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kg</option>
                                    <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram</option>
                                    <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                                    <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>ML</option>
                                    <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack</option>
                                    <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                                    <option value="meter" {{ old('unit') == 'meter' ? 'selected' : '' }}>Meter</option>
                                    <option value="roll" {{ old('unit') == 'roll' ? 'selected' : '' }}>Roll</option>
                                    <option value="buah" {{ old('unit') == 'buah' ? 'selected' : '' }}>Buah</option>
                                </select>
                                @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Harga Satuan -->
                            <div class="col-md-4">
                                <label for="unit_price" class="form-label fw-semibold">Harga per Satuan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" min="1"
                                        class="form-control @error('unit_price') is-invalid @enderror"
                                        id="unit_price" name="unit_price"
                                        value="{{ old('unit_price') }}"
                                        placeholder="0" required>
                                </div>
                                @error('unit_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Keterangan -->
                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">Keterangan</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="3"
                                    placeholder="Keterangan tambahan (opsional)">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Total Price Display -->
                        <div class="card bg-light mt-4 p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-semibold">Total Harga</h6>
                                <h4 class="mb-0 fw-bold text-danger" id="totalPrice">Rp0</h4>
                            </div>
                            <small class="text-muted">
                                <span id="calculation">0 × Rp0 = Rp0</span>
                            </small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('costs.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Pengeluaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const unitPriceInput = document.getElementById('unit_price');
        const unitSelect = document.getElementById('unit');
        const decrementBtn = document.getElementById('decrementQty');
        const incrementBtn = document.getElementById('incrementQty');
        const totalPriceDisplay = document.getElementById('totalPrice');
        const calculationDisplay = document.getElementById('calculation');

        // Quantity controls
        decrementBtn.addEventListener('click', function() {
            let value = parseFloat(quantityInput.value);
            if (value > 0.01) {
                quantityInput.value = Math.max(0.01, value - 1);
                calculateTotal();
            }
        });

        incrementBtn.addEventListener('click', function() {
            let value = parseFloat(quantityInput.value);
            quantityInput.value = value + 1;
            calculateTotal();
        });

        // Calculate total price
        function calculateTotal() {
            const quantity = parseFloat(quantityInput.value) || 0;
            const unitPrice = parseInt(unitPriceInput.value) || 0;
            const unit = unitSelect.value || 'pcs';
            const totalPrice = quantity * unitPrice;

            totalPriceDisplay.textContent = `Rp${totalPrice.toLocaleString('id-ID')}`;
            calculationDisplay.textContent = `${quantity} ${unit} × Rp${unitPrice.toLocaleString('id-ID')} = Rp${totalPrice.toLocaleString('id-ID')}`;
        }

        // Event listeners for calculation
        quantityInput.addEventListener('input', calculateTotal);
        unitPriceInput.addEventListener('input', calculateTotal);
        unitSelect.addEventListener('change', calculateTotal);

        // Initial calculation
        calculateTotal();

        // Format number input on blur
        unitPriceInput.addEventListener('blur', function() {
            const value = parseInt(this.value);
            if (!isNaN(value)) {
                this.value = value;
            }
        });

        // Prevent form submission if total is 0
        document.getElementById('costForm').addEventListener('submit', function(e) {
            const quantity = parseFloat(quantityInput.value) || 0;
            const unitPrice = parseInt(unitPriceInput.value) || 0;

            if (quantity <= 0 || unitPrice <= 0) {
                e.preventDefault();
                alert('Jumlah dan harga satuan harus lebih dari 0!');
                return false;
            }
        });
    });
</script>
@endpush
@endsection