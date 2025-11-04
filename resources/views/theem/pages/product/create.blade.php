@extends('theem.layouts.app')

@section('content')
    <style>
        .tom-select.custom-select {
            width: 100% !important;
            padding: 0.375rem 0.75rem;
            height: calc(2.25rem + 2px);
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            background-color: #fff;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        .ts-wrapper {
            width: 100% !important;
        }
    </style>

    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Add Product</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form data-toggle="validator" method="POST" enctype="multipart/form-data"
                                action="{{ route('product.store') }}">
                                @csrf
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Product Type *</label>
                                            <select class="form-control" name="product_type" id="product_type" required>
                                                <option value="" selected disabled>-- Select Type --</option>
                                                <option value="stock">Stock</option>
                                                <option value="purchase">Purchase</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name *</label>
                                            <input type="text" class="form-control" placeholder="Enter Name"
                                                name="name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Code *</label>
                                            <input type="text" class="form-control" placeholder="Enter Code"
                                                name="product_code" id="product_code" required>
                                        </div>
                                        <small id="code-error" class="text-danger d-none">This code already exists.</small>
                                    </div>

                                    <div class="col-md-6 d-none" id="supplier_group">
                                        <div class="form-group">
                                            <label>Supplier *</label>
                                            <select class="form-control select-search" name="supplier_id"
                                                placeholder="Select Supplier">
                                                <option value="" disabled selected hidden>Select Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category *</label>
                                            <select class="form-control select-search" name="category_id"
                                                placeholder="Select Category" required>
                                                <option value="" disabled selected hidden>Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cost *</label>
                                            <input type="number" step="0.01" class="form-control"
                                                placeholder="Enter Cost" name="cost_price" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Price *</label>
                                            <input type="number" step="0.01" class="form-control"
                                                placeholder="Enter Price" name="sale_price" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Quantity *</label>
                                            <input type="number" class="form-control" placeholder="Enter Quantity"
                                                name="stock_quantity" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Unit *</label>
                                            <select name="unit" class="form-control select-search"
                                                placeholder="Select Unit" required>
                                                <option value="" disabled selected hidden>Select Unit</option>
                                                <option value="Piece">Piece</option>
                                                <option value="Carton">Carton</option>
                                                <option value="Kilogram">Kilogram</option>
                                                <option value="Gram">Gram</option>
                                                <option value="Liter">Liter</option>
                                                <option value="Milliliter">Milliliter</option>
                                                <option value="Dozen">Dozen</option>
                                                <option value="Pack">Pack</option>
                                                <option value="Can">Can</option>
                                                <option value="Bag">Bag</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image</label>
                                            <input type="file" class="form-control" name="image" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description / Product Details</label>
                                            <textarea class="form-control" rows="2" name="description"></textarea>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" id="add-product-btn">Add
                                            Product</button>

                                        <button type="reset" class="btn btn-danger">Reset</button>
                                    </div>
                                    <a href="{{ route('product.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productType = document.getElementById('product_type');
            const supplierGroup = document.getElementById('supplier_group');

            productType.addEventListener('change', function() {
                if (this.value === 'purchase') {
                    supplierGroup.classList.remove('d-none');
                } else {
                    supplierGroup.classList.add('d-none');
                }
            });

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

            const existingCodes = @json($existingCodes);

            const codeInput = document.getElementById('product_code');
            const errorElement = document.getElementById('code-error');
            const submitButton = document.getElementById('add-product-btn');

            codeInput.addEventListener('input', function() {
                const code = this.value.trim();
                if (existingCodes.includes(code)) {
                    errorElement.classList.remove('d-none');
                    submitButton.disabled = true; // اقفل زرار Add Product
                } else {
                    errorElement.classList.add('d-none');
                    submitButton.disabled = false; // افتح الزر
                }
            });
        });
    </script>
@endsection
