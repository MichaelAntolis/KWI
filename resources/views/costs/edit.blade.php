<!-- costs/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom animate-on-scroll">
                <div class="card-header">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-pencil-square me-2"></i> Edit Pengeluaran
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('costs.update', $cost->id) }}" id="costForm">
                        @csrf
                        @method('PUT')

                        <!-- Informasi User -->
                        <div class="alert alert-warning mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Data asal:</strong> Dibuat oleh {{ $cost->user->name }}
                            pada {{ $cost->created_at->format('d F Y, H:i') }}
                            <br>
                            <strong>Anda mengedit:</strong> {{ auth()->user()->name }}
                            pada {{ now()->format('d F Y, H:i') }}
                        </div>

                        <div class="row g-3">
                            <!-- Nama Barang -->
                            <div class="col-md-6">
                                <label for="item_name" class="form-label fw-semibold">Nama Barang/Bahan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('item_name') is-invalid @enderror"
                                    id="item_name" name="item_name"
                                    value="{{ old('item_name', $cost->item_name) }}"
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
                                    value="{{ old('purchased_date', $cost->purchased_date->format('Y-m-d')) }}" required>
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
                                        value="{{ old('quantity', $cost->quantity) }}" required>
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
                                    <option value="pcs" {{ old('unit', $cost->unit) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                    <option value="kg" {{ old('unit', $cost->unit) == 'kg' ? 'selected' : '' }}>Kg</option>
                                    <option value="gram" {{ old('unit', $cost->unit) == 'gram' ? 'selected' : '' }}>Gram</option>
                                    <option value="liter" {{ old('unit', $cost->unit) == 'liter' ? 'selected' : '' }}>Liter</option>
                                    <option value="ml" {{ old('unit', $cost->unit) == 'ml' ? 'selected' : '' }}>ML</option>
                                    <option value="pack" {{ old('unit', $cost->unit) == 'pack' ? 'selected' : '' }}>Pack</option>
                                    <option value="box" {{ old('unit', $cost->unit) == 'box' ? 'selected' : '' }}>Box</option>
                                    <option value="meter" {{ old('unit', $cost->unit) == 'meter' ? 'selected' : '' }}>Meter</option>
                                    <option value="roll" {{ old('unit', $cost->unit) == 'roll' ? 'selected' : '' }}>Roll</option>
                                    <option value="buah" {{ old('unit', $cost->unit) == 'buah' ? 'selected' : '' }}>Buah</option>
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
                                        value="{{ old('unit_price', $cost->unit_price) }}"
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
                                    placeholder="Keterangan tambahan (opsional)">{{ old('description', $cost->description) }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Total Price Display -->
                        <div class="card bg-light mt-4 p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-semibold">Total Harga</h6>
                                    <small class="text-muted">Harga sebelumnya: Rp{{ number_format($cost->total_price, 0, ',', '.') }}</small>
                                </div>
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
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-danger"
                                    onclick="if(confirm('Yakin ingin menghapus data ini?')) { document.getElementById('deleteForm').submit(); }">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Perbarui
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Delete Form -->
                    <form id="deleteForm" action="{{ route('costs.destroy', $cost->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
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