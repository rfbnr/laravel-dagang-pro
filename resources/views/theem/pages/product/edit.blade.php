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
                                <h4 class="card-title">Update Product</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form data-toggle="validator" method="POST" enctype="multipart/form-data"
                                action="{{ route('product.update', ['product' => $product->id ?? 0]) }}">
                                @csrf
                                @method('PUT')
                                @if (request()->has('from'))
                                    <input type="hidden" name="from" value="{{ request()->get('from') }}">
                                @endif
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name *</label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ old('name', $product->name) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Code *</label>
                                            <input type="text" class="form-control" name="product_code" id="product_code"
                                                value="{{ old('product_code', $product->product_code) }}" required>
                                        </div>
                                        <small id="code-error" class="text-danger d-none">This code already exists.</small>
                                    </div>

                                    @if (!empty($product->supplier_id))
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Supplier *</label>
                                                <select name="supplier_id" class="form-control select-search"
                                                    placeholder="Select Supplier">
                                                    <option value="" disabled hidden>Select Supplier</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}"
                                                            {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                            {{ $supplier->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category *</label>
                                            <select name="category_id" class="form-control select-search"
                                                placeholder="Select Category" required>
                                                <option value="" disabled hidden>Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cost *</label>
                                            <input type="number" step="0.01" class="form-control" name="cost_price"
                                                value="{{ old('cost_price', $product->cost_price) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Price *</label>
                                            <input type="number" step="0.01" class="form-control" name="sale_price"
                                                value="{{ old('sale_price', $product->sale_price) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Quantity *</label>
                                            <input type="number" class="form-control" name="stock_quantity"
                                                value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Unit *</label>
                                            <select name="unit" class="form-control select-search"
                                                placeholder="Select Unit" required>
                                                <option value="" disabled hidden>Select Unit</option>
                                                @foreach (['Piece', 'Carton', 'Kilogram', 'Gram', 'Liter', 'Milliliter', 'Dozen', 'Pack', 'Can', 'Bag'] as $unit)
                                                    <option value="{{ $unit }}"
                                                        {{ old('unit', $product->unit) == $unit ? 'selected' : '' }}>
                                                        {{ $unit }}
                                                    </option>
                                                @endforeach
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
                                            <textarea name="description" class="form-control" rows="2">{{ old('description', $product->description) }}</textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">Update Product</button>
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                    </div>
                                    <a href="{{ request()->from == 'low-stock' ? route('product.lowStock') : route('product.index') }}"
                                        class="btn btn-outline-secondary btn-lg">
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
            document.querySelectorAll('.select-search').forEach((el) => {
                new TomSelect(el, {
                    create: false,
                    hidePlaceholder: true,
                    placeholder: el.getAttribute('placeholder'),
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
                el.classList.add('custom-select');
            });

            const existingCodes = @json($existingCodes);

            document.getElementById('product_code').addEventListener('input', function() {
                const code = this.value.trim();
                const errorElement = document.getElementById('code-error');
                if (existingCodes.includes(code) && code !== "{{ $product->product_code }}") {
                    errorElement.classList.remove('d-none');
                } else {
                    errorElement.classList.add('d-none');
                }
            });
        });
    </script>
@endsection
