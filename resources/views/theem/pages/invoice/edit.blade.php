@extends('theem.layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Edit Invoice #{{ $invoices->invoice_number }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('invoice.update', $invoices->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="row">



                                    {{-- Customer Section --}}
                                    @if ($invoices->customer_id)
                                        {{-- Existing Customer Select --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Select Customer *</label>
                                                <select name="customer_id" class="form-control" required>
                                                    <option value="">-- Select Customer --</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}"
                                                            {{ old('customer_id', $invoices->customer_id) == $customer->id ? 'selected' : '' }}>
                                                            {{ $customer->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @elseif($invoices->customer_name)
                                        {{-- Walk-in Customer Input --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Customer Name *</label>
                                                <input type="text" name="customer_name" class="form-control"
                                                    value="{{ old('customer_name', $invoices->customer_name) }}" required>
                                            </div>
                                        </div>
                                    @endif




                                    {{-- Status --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status *</label>
                                            <select name="status" class="form-control" required>
                                                <option value="pending"
                                                    {{ old('status', $invoices->status) == 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="paid"
                                                    {{ old('status', $invoices->status) == 'paid' ? 'selected' : '' }}>Paid
                                                </option>
                                                <option value="canceled"
                                                    {{ old('status', $invoices->status) == 'canceled' ? 'selected' : '' }}>
                                                    Canceled</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Discount --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Discount</label>
                                            <input type="number" class="form-control" step="0.01" name="discount"
                                                value="{{ old('discount', $invoices->discount) }}">
                                        </div>
                                    </div>

                                    {{-- Notes --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Notes</label>
                                            <textarea class="form-control" name="notes" rows="1">{{ old('notes', $invoices->notes) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <h5 class="mb-3">Invoice Items</h5>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Tax %</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoices->items as $index => $item)
                                                <tr>
                                                    <td>
                                                        {{ optional($item->product)->name ?? '-' }}
                                                        <input type="hidden" name="items[{{ $index }}][id]"
                                                            value="{{ $item->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control quantity"
                                                            name="items[{{ $index }}][quantity]"
                                                            value="{{ $item->quantity }}" required min="1"
                                                            data-original="{{ $item->quantity }}"
                                                            oninput="checkStockLimit(this)">

                                                        <small class="text-danger stock-warning d-none"></small>
                                                    </td>

                                                    <td>
                                                        <input type="number" step="0.01" class="form-control"
                                                            name="items[{{ $index }}][unit_price]"
                                                            value="{{ $item->unit_price }}" required readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" class="form-control"
                                                            name="items[{{ $index }}][tax_rate]"
                                                            value="{{ $item->tax_rate }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6 mt-4">
                                    <div class="alert alert-info d-flex justify-content-between align-items-center shadow-sm"
                                        role="alert">
                                        <strong class="h5 mb-0">Grand Total:</strong>
                                        <input type="text" name="total_amount" id="grand_total"
                                            class="form-control text-end fw-bold w-25 bg-white border-0 fs-4"
                                            style="box-shadow: none;" readonly>
                                    </div>
                                </div>


                                {{-- Buttons --}}
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" id="add-product-btn">Update
                                            Invoice</button>
                                    </div>
                                    <a href="{{ route('invoice.index') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="bi bi-arrow-left-circle me-2"></i> Back
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    const productData = {!! $products->mapWithKeys(
            fn($p) => [
                $p->id => [
                    'name' => $p->name,
                    'stock_quantity' => $p->stock_quantity,
                ],
            ],
        )->toJson() !!};

    document.addEventListener('DOMContentLoaded', function() {
        const invoiceType = '{{ $invoices->type }}';

        document.querySelectorAll('.quantity').forEach(input => {
            // On Input: Allow typing but validate immediately
            input.addEventListener('input', function() {
                validateQuantity(this);
                calculateGrandTotal();
            });

            // On Blur: Final check when user leaves the field
            input.addEventListener('blur', function() {
                if (parseInt(this.value) < 1 || isNaN(this.value)) {
                    this.value = 1;
                }
            });
        });

        function validateQuantity(inputElement) {
            const row = inputElement.closest('tr');
            const warningElement = row.querySelector('.stock-warning');
            const productName = row.querySelector('td:first-child').innerText.trim();
            const maxStock = getProductStock(productName);

            let qty = parseInt(inputElement.value);

            // Prevent empty, zero, or negative values
            if (isNaN(qty) || qty < 1) {
                qty = '';
                inputElement.value = qty;
                warningElement.innerText = `Quantity must be at least 1.`;
                warningElement.classList.remove('d-none');
                return;
            } else {
                warningElement.classList.add('d-none');
            }

            // Check Stock Limit for Sale Invoices
            if (invoiceType === 'sale') {
                const originalQty = parseInt(inputElement.dataset.original) || 0;
                const allowedQty = maxStock + originalQty;

                if (qty > allowedQty) {
                    inputElement.value = allowedQty;
                    warningElement.innerText = `Only ${allowedQty} units allowed (Stock + Existing Invoice).`;
                    warningElement.classList.remove('d-none');
                } else {
                    warningElement.classList.add('d-none');
                }
            }
        }

        function getProductStock(productName) {
            for (const [id, product] of Object.entries(productData)) {
                if (product.name === productName) {
                    return product.stock_quantity;
                }
            }
            return 0;
        }
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تعريف العناصر
        const quantityInputs = document.querySelectorAll('input[name*="[quantity]"]');
        const taxInputs = document.querySelectorAll('input[name*="[tax_rate]"]');
        const discountInput = document.querySelector('input[name="discount"]');
        const grandTotalInput = document.getElementById('grand_total');

        // دالة حساب الإجمالي
        function calculateGrandTotal() {
            let total = 0;
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
                const unitPrice = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) ||
                    0;
                const taxRate = parseFloat(row.querySelector('input[name*="[tax_rate]"]').value) || 0;

                const subtotal = quantity * unitPrice;
                const tax = (subtotal * taxRate) / 100;
                total += subtotal + tax;
            });

            // خصم الدسكاونت من الإجمالي
            const discount = parseFloat(discountInput.value) || 0;
            const finalTotal = Math.max(0, total - discount);

            // عرض الإجمالي
            grandTotalInput.value = finalTotal.toFixed(2);
        }

        // إضافة مستمعي الأحداث
        quantityInputs.forEach(input => {
            input.addEventListener('input', calculateGrandTotal);
        });

        taxInputs.forEach(input => {
            input.addEventListener('input', calculateGrandTotal);
        });

        discountInput.addEventListener('input', calculateGrandTotal);

        // حساب الإجمالي عند تحميل الصفحة
        calculateGrandTotal();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- Elements ---
        const discountInput = document.querySelector('input[name="discount"]');
        const itemRows = document.querySelectorAll('tbody tr');
        const grandTotalField = document.getElementById('grand_total');
        const customerTypeSelect = document.getElementById('customer_type');
        const existingCustomerSection = document.getElementById('existing_customer_section');
        const walkinCustomerSection = document.getElementById('walkin_customer_section');
        const customerSelect = document.getElementById('customer_id');
        const customerNameInput = document.getElementById('customer_name');

        // --- Functions ---
        function calculateTotal() {
            let subtotal = 0;

            itemRows.forEach(row => {
                const qty = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
                const price = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
                const taxRate = parseFloat(row.querySelector('input[name*="[tax_rate]"]').value) || 0;

                const rowTotal = qty * price;
                const tax = (rowTotal * taxRate) / 100;
                subtotal += rowTotal + tax;
            });

            const discount = parseFloat(discountInput.value) || 0;
            let finalTotal = subtotal - discount;
            if (finalTotal < 0) finalTotal = 0;

            grandTotalField.value = finalTotal.toFixed(2);
        }

        function toggleCustomerFields() {
            if (customerTypeSelect.value === 'walkin') {
                existingCustomerSection.style.display = 'none';
                walkinCustomerSection.style.display = 'block';
                customerSelect.required = false;
                customerNameInput.required = true;
            } else {
                existingCustomerSection.style.display = 'block';
                walkinCustomerSection.style.display = 'none';
                customerSelect.required = true;
                customerNameInput.required = false;
            }
        }

        function attachEventListeners() {
            discountInput.addEventListener('input', calculateTotal);

            itemRows.forEach(row => {
                row.querySelectorAll('input').forEach(input => {
                    input.addEventListener('input', calculateTotal);
                });
            });

            customerTypeSelect.addEventListener('change', toggleCustomerFields);
        }

        // --- Init ---
        calculateTotal();
        toggleCustomerFields();
        attachEventListeners();

    });
</script>
