@extends('theem.layouts.app')

<style>
    .tom-select.custom-select {
        width: 100% !important;
        padding: 0.375rem 0.75rem;
        height: calc(2.25rem + 2px);
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        background-color: #fff;
    }

    .ts-wrapper {
        width: 100% !important;
    }
</style>


@section('content')

    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Create Invoice</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="invoice-form" action="{{ route('invoice.store') }}" method="POST">

                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row">

                                    {{-- Invoice Type --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Invoice Type *</label>
                                            <select name="type" id="type" class="form-control" required>
                                                <option value="sale">Sale</option>
                                                <option value="purcash">Purchase</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Customer Type --}}
                                    <div class="col-md-6" id="customer_type_group">
                                        <div class="form-group">
                                            <label>Customer Type *</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="customer_type"
                                                    id="type_existing" value="regular" checked>
                                                <label class="form-check-label" for="type_existing">Existing
                                                    Customer</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="customer_type"
                                                    id="type_walkin" value="walkin">
                                                <label class="form-check-label" for="type_walkin">Walk-in Customer</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Customer Select --}}
                                    <div class="col-md-6" id="customer_dynamic_group">
                                        <div class="form-group">
                                            <label for="customer_id" id="customer_label">Select Customer *</label>
                                            <select name="customer_id" id="customer_id" class="form-control select-search"
                                                placeholder="Select Customer">
                                                <option value="">-- Select Customer --</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>

                                            <input type="text" name="customer_name" id="customer_name"
                                                class="form-control d-none mt-2" placeholder="Enter walk-in customer name">
                                        </div>
                                    </div>

                                    {{-- Supplier Select --}}
                                    <div class="col-md-6 d-none" id="supplier_group">
                                        <div class="form-group">
                                            <label>Select Supplier *</label>
                                            <select name="supplier_id" id="supplier_id" class="form-control select-search"
                                                placeholder="Select Supplier">
                                                <option value="">-- Select Supplier --</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    {{-- Products --}}
                                    <div class="col-md-12">
                                        <label>Products *</label>
                                        <table class="table table-bordered mt-2">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Qty</th>
                                                    <th>Unit Price</th>
                                                    <th>Total</th>
                                                    <th>
                                                        <button type="button" class="btn btn-sm btn-success"
                                                            onclick="addProductRow()">+</button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="products-table"></tbody>
                                        </table>
                                    </div>

                                    {{-- Discount & Tax --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="discount">Discount</label>
                                            <input type="number" name="discount" id="discount" class="form-control"
                                                value="0" min="0" oninput="handleDiscountChange()">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tax">Tax (%)</label>
                                            <input type="number" name="tax_amount" id="tax" class="form-control"
                                                value="{{ old('tax', $defaultTaxRate) }}" oninput="updateInvoiceTotal()">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_amount">Grand Total</label>
                                            <input type="text" id="total_amount" name="total_amount" class="form-control"
                                                readonly>
                                        </div>
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status *</label>
                                            <select name="status" class="form-control" required>
                                                <option value="pending"
                                                    {{ old('status', 'paid') == 'pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="paid"
                                                    {{ old('status', 'paid') == 'paid' ? 'selected' : '' }}>Paid</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Notes --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notes">Notes</label>
                                            <textarea name="notes" class="form-control" rows="1"></textarea>
                                        </div>
                                    </div>

                                    {{-- Buttons --}}
                                    <div class="d-flex justify-content-between align-items-center mt-4">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary" id="add-product-btn">Save
                                                Invoice</button>

                                            <button type="reset" class="btn btn-danger">Reset</button>
                                        </div>
                                        <a href="{{ route('invoice.index') }}" class="btn btn-outline-secondary btn-lg">
                                            <i class="bi bi-arrow-left-circle me-2"></i> Back
                                        </a>
                                    </div>



                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
    const productData = {!! $products->mapWithKeys(
            fn($p) => [
                $p->id => [
                    'name' => $p->name,
                    'sale_price' => $p->sale_price,
                    'cost_price' => $p->cost_price,
                    'stock_quantity' => $p->stock_quantity,
                ],
            ],
        )->toJson() !!};

    let rowIndex = 0;

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.select-search').forEach((el) => {
            new TomSelect(el, {
                create: false,
                hidePlaceholder: true,
                persist: false,
                searchField: ['text'],
                selectOnTab: true,
                placeholder: el.getAttribute('placeholder'),
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
            el.classList.add('custom-select');
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('invoice-form');
        const typeSelect = document.getElementById('type');
        const customerTypeGroup = document.getElementById('customer_type_group');
        const customerDynamicGroup = document.getElementById('customer_dynamic_group');
        const supplierGroup = document.getElementById('supplier_group');
        const customerIdSelect = document.getElementById('customer_id');
        const customerNameInput = document.getElementById('customer_name');
        const supplierIdSelect = document.getElementById('supplier_id');

        function toggleFields() {
            if (typeSelect.value === 'purcash') {
                // Hide customer fields
                customerTypeGroup.classList.add('d-none');
                customerDynamicGroup.classList.add('d-none');
                supplierGroup.classList.remove('d-none');

                // Remove required from customer fields
                customerIdSelect.removeAttribute('required');
                customerNameInput.removeAttribute('required');

                // Make supplier required
                supplierIdSelect.setAttribute('required', 'required');

                // Clear customer values
                customerIdSelect.value = '';
                customerNameInput.value = '';
            } else {
                // Show customer fields
                customerTypeGroup.classList.remove('d-none');
                customerDynamicGroup.classList.remove('d-none');
                supplierGroup.classList.add('d-none');

                // Remove required from supplier
                supplierIdSelect.removeAttribute('required');
                supplierIdSelect.value = '';

                // Handle customer fields based on type
                const isWalkIn = document.getElementById('type_walkin').checked;
                if (isWalkIn) {
                    customerNameInput.setAttribute('required', 'required');
                    customerIdSelect.removeAttribute('required');
                } else {
                    customerIdSelect.setAttribute('required', 'required');
                    customerNameInput.removeAttribute('required');
                }
            }
        }

        function toggleCustomerType() {
            const isWalkIn = document.getElementById('type_walkin').checked;
            const customerSelect = document.getElementById('customer_id');
            const customerName = document.getElementById('customer_name');
            const customerLabel = document.querySelector('label[for="customer_id"]');
            const tomSelect = customerSelect.tomselect;
            const customerDynamicGroup = document.getElementById('customer_dynamic_group');
            const sectionTitle = customerDynamicGroup.querySelector('.form-group label:first-child');

            if (isWalkIn) {
                // إخفاء select customer وإظهار حقل إدخال الاسم
                if (tomSelect) tomSelect.wrapper.style.display = 'none';
                customerName.classList.remove('d-none');
                customerName.setAttribute('required', 'required');
                customerSelect.removeAttribute('required');

                // تغيير العنوان لحالة Walk-in
                if (sectionTitle) sectionTitle.innerText = 'Add Customer *';
                customerLabel.innerText = 'Customer Name *';
            } else {
                // إظهار select customer وإخفاء حقل إدخال الاسم
                if (tomSelect) tomSelect.wrapper.style.display = '';
                customerName.classList.add('d-none');
                customerSelect.setAttribute('required', 'required');
                customerName.removeAttribute('required');

                // تغيير العنوان لحالة Existing
                if (sectionTitle) sectionTitle.innerText = 'Select Customer *';
                customerLabel.innerText = 'Select Customer *';
            }
        }

        // إضافة مستمعي الأحداث
        document.addEventListener('DOMContentLoaded', function() {
            // ...existing code...

            document.getElementById('type_existing').addEventListener('change', toggleCustomerType);
            document.getElementById('type_walkin').addEventListener('change', toggleCustomerType);

            // تشغيل الدالة عند تحميل الصفحة
            toggleCustomerType();
        });

        function resetCustomerFields() {
            customerIdSelect.value = '';
            customerNameInput.value = '';
            supplierIdSelect.value = '';
        }

        document.getElementById('type_existing').addEventListener('change', toggleCustomerType);
        document.getElementById('type_walkin').addEventListener('change', toggleCustomerType);

        typeSelect.addEventListener('change', function() {
            toggleFields();
            resetCustomerFields();

            document.querySelectorAll('.product-select').forEach(select => {
                if (select.value) {
                    updateUnitPrice(select, true); // Skip duplicate check on type change
                }
            });

            document.querySelectorAll('.quantity').forEach(input => checkStockLimit(input));
        });

        // Replace the existing form submission event listener with this updated version:
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const type = typeSelect.value;
            const supplierSelect = supplierIdSelect;
            const customerSelect = customerIdSelect;
            const customerName = customerNameInput;
            const isWalkIn = document.getElementById('type_walkin').checked;

            // Validation for Purchase invoices
            if (type === 'purcash') {
                if (!supplierSelect.value) {
                    alert('Please select a supplier');
                    return false;
                }
            }
            // Validation for Sale invoices
            else if (type === 'sale') {
                if (!isWalkIn && !customerSelect.value) {
                    alert('Please select a customer');
                    return false;
                }
                if (isWalkIn && !customerName.value.trim()) {
                    alert('Please enter customer name');
                    return false;
                }
            }

            // Validate products
            const products = document.querySelectorAll('.product-select');
            if (products.length === 0) {
                alert('Please add at least one product');
                return false;
            }

            let hasProducts = false;
            products.forEach(product => {
                if (product.value) {
                    hasProducts = true;
                }
            });

            if (!hasProducts) {
                alert('Please select at least one product');
                return false;
            }

            // If all validations pass, submit the form
            this.submit();
        });

        toggleFields();
        toggleCustomerType();
    });

    function addProductRow() {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${getProductSelectHtml(rowIndex)}</td>
            <td>
                <input type="number" name="products[${rowIndex}][quantity]" class="form-control quantity" value="0" min="0"
                    oninput="checkStockLimit(this)" required>
                <small class="text-danger stock-warning d-none"></small>
            </td>
            <td><input type="text" name="products[${rowIndex}][unit_price]" class="form-control unit-price" readonly></td>
            <td><input type="text" name="products[${rowIndex}][total_price]" class="form-control total-price" readonly></td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">-</button></td>
        `;
        document.getElementById('products-table').appendChild(row);
        rowIndex++;
        updateInvoiceTotal();
    }

    function getProductSelectHtml(index) {
        let options = `<option value="">-- Select Product --</option>`;
        for (const [id, data] of Object.entries(productData)) {
            options += `<option value="${id}">${data.name}</option>`;
        }
        return `<select name="products[${index}][product_id]" class="form-control product-select" onchange="updateUnitPrice(this)" required>${options}</select>`;
    }

    function updateUnitPrice(select, skipDuplicateCheck = false) {
        const selectedProduct = select.value;
        const row = select.closest('tr');
        const invoiceType = document.getElementById('type').value;

        if (!skipDuplicateCheck && isProductDuplicate(selectedProduct)) {
            alert("This product is already selected.");
            select.value = "";
            return;
        }

        const unitPriceInput = row.querySelector('.unit-price');
        const product = productData[selectedProduct];

        if (product) {
            unitPriceInput.value = (invoiceType === 'purcash') ? product.cost_price : product.sale_price;
        } else {
            unitPriceInput.value = 0;
        }

        const qtyInput = row.querySelector('.quantity');
        qtyInput.value = qtyInput.value || 0;
        row.querySelector('.stock-warning').classList.add('d-none');

        updateRowTotal(select);
        updateInvoiceTotal();
    }

    function checkStockLimit(inputElement) {

        // تحويل القيمة السالبة إلى موجبة تلقائيًا
        if (parseInt(inputElement.value) < 0) {
            inputElement.value = Math.abs(inputElement.value);
        }

        const row = inputElement.closest('tr');
        const selectElement = row.querySelector('.product-select');
        const productId = selectElement.value;
        const warningElement = row.querySelector('.stock-warning');
        const invoiceType = document.getElementById('type').value;

        if (productId && productData[productId]) {
            const maxStock = productData[productId].stock_quantity;
            let qty = parseInt(inputElement.value) || 0;

            if (invoiceType === 'sale') {
                if (qty > maxStock) {
                    inputElement.value = maxStock;
                    warningElement.innerText = `Only ${maxStock} units available in stock.`;
                    warningElement.classList.remove('d-none');
                } else {
                    warningElement.classList.add('d-none');
                }
            } else {
                warningElement.classList.add('d-none');
            }

            updateRowTotal(inputElement);
        }
    }

    function updateRowTotal(element) {
        const row = element.closest('tr');
        const qty = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const total = qty * unitPrice;
        row.querySelector('.total-price').value = total.toFixed(2);
        updateInvoiceTotal();
    }

    function updateInvoiceTotal() {
        let total = 0;
        document.querySelectorAll('.total-price').forEach(input => {
            total += parseFloat(input.value) || 0;
        });

        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const taxRate = parseFloat(document.getElementById('tax').value) || 0;

        let totalWithDiscount = total - discount;
        if (totalWithDiscount < 0) totalWithDiscount = 0;

        const tax = (totalWithDiscount * taxRate) / 100;
        const finalTotal = totalWithDiscount + tax;

        document.getElementById('total_amount').value = finalTotal.toFixed(2);
    }

    function handleDiscountChange() {
        const discountInput = document.getElementById('discount');
        let value = parseFloat(discountInput.value);
        if (value < 0) {
            discountInput.value = 0;
        }
        updateInvoiceTotal();
    }

    function removeRow(button) {
        const row = button.closest('tr');
        row.remove();
        updateInvoiceTotal();
    }

    function isProductDuplicate(productId) {
        const selects = document.querySelectorAll('.product-select');
        let count = 0;
        selects.forEach(s => {
            if (s.value == productId) count++;
        });
        return count > 1;
    }
</script>
