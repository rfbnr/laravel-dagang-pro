@extends('theem.layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-3">Low Stock List</h4>
                            <p class="mb-0">Here you can manage your products list.</p>

                        </div>

                    </div>
                </div>

                @if (session('success'))
                    <div id="success-alert" class="alert alert-success alert-dismissible fade" role="alert"
                        style="opacity: 0;">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif


                <div class="col-lg-12">
                    <div class="table-responsive rounded mb-3">
                        <table class="data-tables table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th>
                                        <div class="checkbox d-inline-block">
                                            <input type="checkbox" class="checkbox-input" id="checkbox1">
                                            <label for="checkbox1" class="mb-0"></label>
                                        </div>
                                    </th>
                                    <th>Product</th>
                                    <th>Code</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Supplier</th>
                                    <th>Cost</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody class="ligth-body">
                                @foreach ($lowStockProducts as $lowStockProduct)
                                    <tr>
                                        <td>
                                            <div class="checkbox d-inline-block">
                                                <input type="checkbox" class="checkbox-input" id="checkbox2">
                                                <label for="checkbox2" class="mb-0"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset($lowStockProduct->image_path) }}"
                                                    class="img-fluid rounded avatar-50 me-3"
                                                    alt="{{ $lowStockProduct->name }}">
                                                <div style="margin-left: 10px;">
                                                    <strong>{{ $lowStockProduct->name }}</strong>
                                                    <p class="mb-0 text-muted">
                                                        <small>{{ \Illuminate\Support\Str::words(strip_tags($lowStockProduct->description), 4, '...') }}</small>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <td>{{ $lowStockProduct->product_code }}</td>
                                        <td>{{ optional($lowStockProduct->category)->name }}</td>
                                        <td>{{ number_format($lowStockProduct->sale_price, 2) }} IDR</td>
                                        <td>
                                            @if ($lowStockProduct->supplier)
                                                {{ $lowStockProduct->supplier->name }}
                                            @else
                                                <span class="badge bg-secondary">No Supplier</span>
                                            @endif
                                        </td>

                                        <td>{{ number_format($lowStockProduct->cost_price, 2) }} IDR</td>
                                        <td>{{ $lowStockProduct->stock_quantity }}</td>
                                        <td>
                                            <div class="d-flex align-items-center list-action">
                                                <!-- View -->
                                                {{-- <a class="badge-action badge-view" data-toggle="tooltip" title="View"
                                                    href="{{ route('product.show', ['product' => $product->id]) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a> --}}

                                                <!-- Edit -->
                                                <a class="badge-action badge-edit" data-toggle="tooltip" title="Edit"
                                                    href="{{ route('product.edit', ['product' => $lowStockProduct->id]) }}?from=low-stock">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <!-- Page end  -->
        </div>
    @endsection
