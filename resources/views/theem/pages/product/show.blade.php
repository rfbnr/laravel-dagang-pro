@extends('theem.layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">

                    <!-- Title -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Product Details</h4>
                    </div>

                    <!-- Main Card (Image + Info) -->
                    <div class="card shadow border-0 rounded-4 overflow-hidden mb-4 fade-slide-in">

                        <div class="row g-0 h-100">
                            <!-- Left Side: Image -->
                            {{-- <div class="col-md-4 d-flex align-items-center justify-content-center bg-light p-3"
                                style="border-right: 1px solid #e0e0e046;"> --}}
                            <div class="col-md-4 d-flex align-items-center justify-content-center p-3"
                                style="border-right: 1px solid #dee2e6; background-color: #f0f2f5;">

                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="img-fluid rounded border border-2 border-primary product-image-hover"
                                    style="max-height: 200px; object-fit: contain;">


                            </div>

                            <!-- Right Side: Info -->
                            <div class="col-md-8">
                                <div class="card-body py-4 px-5">
                                    <h4 class="card-title fw-bold text-primary mb-3">{{ $product->name }}</h4>
                                    <ul class="list-group list-group-flush mb-3 small">
                                        <li class="list-group-item px-0">
                                            <strong>Supplier:</strong>
                                            @if ($product->supplier)
                                                {{ $product->supplier->name }}
                                            @else
                                                <span class="text-muted">No Supplier</span>
                                            @endif
                                        </li>

                                        <li class="list-group-item px-0"><strong>Category:</strong>
                                            {{ $product->category->name }}</li>
                                        <li class="list-group-item px-0"><strong>Code:</strong> {{ $product->product_code }}
                                        </li>
                                        <li class="list-group-item px-0"><strong>Unit:</strong>
                                            {{ $product->unit ?? 'N/A' }}</li>
                                        <li class="list-group-item px-0"><strong>Cost:</strong>
                                            {{ number_format($product->cost_price, 2) }} IDR</li>
                                        <li class="list-group-item px-0"><strong>Price:</strong>
                                            {{ number_format($product->sale_price, 2) }} IDR</li>
                                        <li class="list-group-item px-0"><strong>Quantity:</strong>
                                            {{ $product->stock_quantity }}</li>
                                    </ul>
                                    <div class="d-flex justify-content-between small text-muted">
                                        <span>Created At: {{ $product->created_at->format('d M Y') }}</span>
                                        <span>Updated At: {{ $product->updated_at->format('d M Y') }}</span>
                                    </div>
                                    <a href="{{ route('product.index') }}"
                                        class="btn btn-outline-primary btn-sm mt-3 w-100">
                                        Back to Products
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Card -->
                    <div class="card shadow border-0 rounded-4 p-4">
                        <h5 class="mb-3 fw-bold" style="color: #495057; font-size: 1.25rem; letter-spacing: 0.5px;">
                            <i class="bi bi-card-text me-2"></i> Product Description
                        </h5>

                        <p class="text-muted mb-0">
                            {{ $product->description ?? 'No description available.' }}
                        </p>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
