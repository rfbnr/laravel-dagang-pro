<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f9f9f9;
        }

        .text-end {
            text-align: right;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo {
            height: 60px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
        }

        .info {
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
            color: #555;
        }
    </style>
</head>

<body>

    <div class="header">
        <div>
            <div class="title">StockSmart</div>
            <small>Invoice #{{ $invoice->invoice_number }}</small>
        </div>
        {{-- <div>
            <img src="{{ public_path('assets/logo.png') }}" class="logo" alt="Logo">

        </div> --}}
    </div>

    <div class="info">
        <strong>Type:</strong> {{ $invoice->type === 'sale' ? 'Sale (Customer)' : 'Purchase (Supplier)' }}<br>
        <strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->date)->format('d M Y') }}<br>
        <strong>Status:</strong> {{ ucfirst($invoice->status) }}
    </div>

    <div class="info">
        <strong>
            {{ $invoice->type === 'sale' ? 'Customer Info' : 'Supplier Info' }}:
        </strong><br>
        @if ($invoice->type === 'sale')
            {{ optional($invoice->customer)->name ?? ($invoice->customer_name ?? 'Walk-in Customer') }}<br>
            {{ optional($invoice->customer)->email ?? '-' }}<br>
            {{ optional($invoice->customer)->phone ?? '-' }}
        @else
            {{ optional($invoice->supplier)->name ?? '-' }}<br>
            {{ optional($invoice->supplier)->email ?? '-' }}<br>
            {{ optional($invoice->supplier)->phone ?? '-' }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th class="text-end">Qty</th>
                <th class="text-end">Unit Price</th>
                <th class="text-end">Tax %</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ optional($item->product)->name ?? '-' }}</td>
                    <td class="text-end">{{ $item->quantity }}</td>
                    <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-end">{{ $item->tax_rate ?? 0 }}%</td>
                    <td class="text-end">{{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $subtotal = $invoice->items->sum('total_price');
        $tax = $invoice->items->sum(fn($item) => ($item->unit_price * $item->quantity * ($item->tax_rate ?? 0)) / 100);
        $discount = $invoice->discount ?? 0;
        $total = $subtotal + $tax - $discount;
    @endphp

    <table style="margin-top: 20px;">
        <tr>
            <th class="text-end" colspan="5">Subtotal</th>
            <td class="text-end">{{ number_format($subtotal, 2) }}</td>
        </tr>
        <tr>
            <th class="text-end" colspan="5">Discount</th>
            <td class="text-end">{{ number_format($discount, 2) }}</td>
        </tr>
        <tr>
            <th class="text-end" colspan="5">Tax</th>
            <td class="text-end">{{ number_format($tax, 2) }}</td>
        </tr>
        <tr>
            <th class="text-end" colspan="5">Grand Total</th>
            <td class="text-end">{{ number_format($total, 2) }}</td>
        </tr>
    </table>

    {{-- ✅ اسم الكاشير --}}
    <div class="footer">
        <strong>Cashier:</strong> {{ optional($invoice->user)->name ?? 'N/A' }}
    </div>

</body>

</html>
