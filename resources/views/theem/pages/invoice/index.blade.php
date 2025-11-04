@extends('theem.layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-3">Invoices List</h4>
                            <p class="mb-0">Here you can view and manage all invoices in the system.</p>
                        </div>
                        <a href="{{ route('invoice.create') }}" class="btn btn-primary add-list">
                            <i class="las la-plus mr-3"></i>Create Invoice
                        </a>
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
                                    <th>Invoice #</th>
                                    <th>Type</th>
                                    <th>Customer / Supplier</th>
                                    {{-- <th>Date</th> --}}
                                    <th>Status</th>
                                    <th>Grand Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody class="ligth-body">
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <!-- Invoice Number -->
                                        <td>#INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>

                                        <!-- Type -->
                                        <td>
                                            @if ($invoice->type == 'sale')
                                                <span class="badge badge-success">Sale</span>
                                            @else
                                                <span class="badge badge-info">Purchase</span>
                                            @endif
                                        </td>

                                        <!-- Customer / Supplier -->
                                        <td>
                                            @if ($invoice->type == 'sale')
                                                <span class="badge badge-light">Customer</span>
                                                {{ optional($invoice->customer)->name ?? ($invoice->customer_name ?? 'Walk-in Customer') }}
                                            @elseif($invoice->type == 'purcash')
                                                <span class="badge badge-light">Supplier</span>
                                                {{ optional($invoice->supplier)->name ?? 'Unknown Supplier' }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>

                                        <!-- Status -->
                                        <td>
                                            @if ($invoice->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif ($invoice->status == 'paid')
                                                <span class="badge badge-success">Paid</span>
                                            @elseif ($invoice->status == 'canceled')
                                                <span class="badge badge-danger">Canceled</span>
                                            @else
                                                <span class="badge badge-secondary">Unknown</span>
                                            @endif
                                        </td>


                                        <!-- Grand Total -->
                                        <td>{{ number_format($invoice->total_amount, 2) }} IDR</td>

                                        <!-- Actions -->
                                        <td>
                                            <div class="d-flex align-items-center list-action">
                                                <!-- Show -->
                                                <a class="badge-action badge-view" data-toggle="tooltip" title="View"
                                                    href="{{ route('invoice.show', $invoice->id) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>

                                                <!-- Edit -->
                                                <a class="badge-action badge-edit" data-toggle="tooltip" title="Edit"
                                                    href="{{ route('invoice.edit', $invoice->id) }}">
                                                    <i class="ri-pencil-line"></i>
                                                </a>

                                                <!-- Delete -->
                                                <form method="POST" action="{{ route('invoice.destroy', $invoice->id) }}"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="badge-action badge-delete"
                                                        data-toggle="tooltip" title="Delete">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
