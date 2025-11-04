@extends('theem.layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">

                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <div>
                            <h2 class="mb-0 fw-bold text-primary">StockSmart</h2>
                            <small class="text-muted">Your Trusted Inventory Partner</small>
                        </div>
                        <div>
                            <img src="{{ asset('assets/logo.png') }}" alt="Logo" height="50">
                        </div>
                    </div>

                    {{-- Title and Print --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Invoice #{{ $invoice->invoice_number }}</h4>
                        <a href="{{ route('invoice.download', $invoice->id) }}" class="btn btn-primary">
                            <i class="fas fa-print"></i> Print Invoice
                        </a>



                    </div>

                    {{-- Customer/Supplier and Invoice Info --}}
                    <div class="card shadow border-0 rounded-4 mb-4 fade-slide-in">
                        <div class="row g-0">
                            <div class="col-md-6 p-4">
                                <h6 class="fw-bold">{{ $invoice->type === 'sale' ? 'Customer' : 'Supplier' }}</h6>
                                @if ($invoice->type === 'sale')
                                    <p class="mb-1">
                                        {{ optional($invoice->customer)->name ?? ($invoice->customer_name ?? 'Walk-in') }}
                                    </p>
                                    <p class="mb-1">{{ optional($invoice->customer)->email ?? '-' }}</p>
                                    <p class="mb-0">{{ optional($invoice->customer)->phone ?? '-' }}</p>
                                @else
                                    <p class="mb-1">{{ optional($invoice->supplier)->name ?? 'Unknown Supplier' }}</p>
                                    <p class="mb-1">{{ optional($invoice->supplier)->email ?? '-' }}</p>
                                    <p class="mb-0">{{ optional($invoice->supplier)->phone ?? '-' }}</p>
                                @endif
                            </div>
                            <div class="col-md-6 p-4 border-start text-md-end">
                                <h6 class="fw-bold">Invoice Info</h6>
                                <p class="mb-1"><strong>Cashier:</strong> {{ optional($invoice->user)->name ?? '-' }}</p>
                                <p class="mb-1"><strong>Date:</strong>
                                    {{ \Carbon\Carbon::parse($invoice->date)->format('d M Y') }}</p>
                                @if ($invoice->due_date)
                                    <p class="mb-1"><strong>Due Date:</strong>
                                        {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</p>
                                @endif
                                <p class="mb-0">
                                    <strong>Status:</strong>
                                    @if ($invoice->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif ($invoice->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif ($invoice->status === 'canceled')
                                        <span class="badge bg-danger">Canceled</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($invoice->status) }}</span>
                                    @endif
                                </p>

                            </div>
                        </div>
                    </div>

                    {{-- Items Table --}}
                    <div class="card shadow border-0 rounded-4 mb-4 fade-slide-in">
                        <div class="card-header bg-light fw-bold">
                            Invoice Items
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered mb-0">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th class="text-end">Quantity</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Tax %</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->items as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ optional($item->product)->name ?? '-' }}</td>
                                            <td class="text-end">{{ $item->quantity }}</td>
                                            <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                            <td class="text-end">{{ $item->tax_rate ?? 0 }}%</td>
                                            <td class="text-end">{{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    @php
                                        $subtotal = $invoice->items->sum('total_price');
                                        $taxTotal = $invoice->items->sum(
                                            fn($item) => ($item->unit_price *
                                                $item->quantity *
                                                ($item->tax_rate ?? 0)) /
                                                100,
                                        );
                                        $grandTotal = $subtotal + $taxTotal - ($invoice->discount ?? 0);
                                    @endphp
                                    <tr>
                                        <th colspan="5" class="text-end">Subtotal</th>
                                        <th class="text-end">{{ number_format($subtotal, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-end">Total Tax</th>
                                        <th class="text-end">{{ number_format($taxTotal, 2) }}</th>
                                    </tr>
                                    @if ($invoice->discount)
                                        <tr>
                                            <th colspan="5" class="text-end">Discount</th>
                                            <th class="text-end text-danger">-{{ number_format($invoice->discount, 2) }}
                                            </th>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th colspan="5" class="text-end">Grand Total</th>
                                        <th class="text-end text-primary fw-bold">
                                            {{ number_format($invoice->total_amount, 2) }} IDR
                                        </th>
                                    </tr>

                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- Notes --}}
                    @if ($invoice->notes)
                        <div class="card shadow border-0 rounded-4 p-4 fade-slide-in">
                            <h6 class="fw-bold mb-2"><i class="bi bi-sticky me-1"></i> Notes:</h6>
                            <p class="text-muted mb-0">{{ $invoice->notes }}</p>
                        </div>
                    @endif

                    <div class="mt-4 text-center">
                        <a href="{{ route('invoice.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left-circle me-1"></i> Back to Invoices
                        </a>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
