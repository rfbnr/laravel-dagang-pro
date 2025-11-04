<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoices;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Invoices_Item;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoices::with(['user', 'supplier', 'customer', 'items.product'])->paginate(7);
        return view('theem.pages.invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $suppliers = Supplier::all();
        $users = User::all();
        $products = Product::all();
        // $incoicesitems = Invoices_Item::all();

        $defaultTaxRate = Setting::where('key', 'tax_rate')->value('value') ?? 0;

        return view('theem.pages.invoice.create', compact('customers', 'suppliers', 'users', 'products', 'defaultTaxRate',));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $rules = [
            // 'type' => 'required|in:purcash,sale',
            // 'products' => 'required|array',
            // 'products.*.product_id' => 'required|exists:products,id',
            // 'products.*.quantity' => 'required|integer|min:1',
            // 'products.*.unit_price' => 'required|numeric|min:0',
            // 'discount' => 'nullable|numeric|min:0',
            // 'notes' => 'nullable|string',
            // 'total_amount' => 'required|numeric|min:0',
            // 'tax_amount' => 'nullable|numeric|min:0|max:100',

            'type' => 'required|in:sale,purcash',
            'customer_id' => 'required_if:type,sale',
            'customer_name' => 'required_if:type,sale,walk_in',
            'supplier_id' => 'required_if:type,purcash',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'status' => 'required|in:pending,paid,canceled'
        ];

        if ($request->type === 'sale') {
            $rules['customer_type'] = 'required|in:regular,walkin';
            $rules['customer_id'] = 'required_if:customer_type,regular|nullable|exists:customers,id';
            $rules['customer_name'] = 'required_if:customer_type,walkin|nullable|string|max:255';
        }
        if ($request->type === 'purcash') {
            $rules['supplier_id'] = 'required|exists:suppliers,id';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $taxAmount = 0;
            $taxRate = $request->tax_amount ?? 0;

            foreach ($request->products as $item) {
                $lineTotal = $item['unit_price'] * $item['quantity'];
                $totalAmount += $lineTotal;
                $taxAmount += $lineTotal * ($taxRate / 100);
            }

            $discount = $request->discount ?? 0;
            $grandTotal = $totalAmount + $taxAmount - $discount;

            $lastInvoice = Invoices::orderBy('id', 'desc')->first();
            $nextNumber = $lastInvoice ? $lastInvoice->id + 1 : 1;
            $invoiceNumber = 'INV-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

            $customerId = null;
            $customerName = null;

            if ($request->type === 'sale') {
                if ($request->customer_type === 'regular') {
                    $customerId = $request->customer_id;
                    $customerName = Customer::find($customerId)?->name;
                } else {
                    $customerId = null;
                    $customerName = $request->customer_name;
                }
            }

            $invoice = Invoices::create([
                'invoice_number' => $invoiceNumber,
                'type' => $request->type,
                'user_id' => auth()->id(),
                'supplier_id' => $request->type === 'purcash' ? $request->supplier_id : null,
                'customer_id' => $customerId,
                'customer_name' => $customerName,
                'total_amount' => $grandTotal,
                'tax_amount' => $taxAmount,
                'discount' => $discount,
                'status' => $request->status,
                'notes' => $request->notes,
                'date' => now(),
            ]);

            foreach ($request->products as $item) {
                Invoices_Item::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                    'tax_rate' => $taxRate,
                ]);

                $product = Product::findOrFail($item['product_id']);
                if ($request->type === 'sale') {
                    if ($product->stock_quantity < $item['quantity']) {
                        throw new \Exception("Product {$product->name} does not have enough stock.");
                    }
                    $product->stock_quantity -= $item['quantity'];
                } elseif ($request->type === 'purcash') {
                    $product->stock_quantity += $item['quantity'];
                }
                $product->save();
            }

            DB::commit();

            return redirect()->route('invoice.index')->with('success', 'Invoice created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }




    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoice = Invoices::with(['items.product', 'customer', 'supplier', 'user'])->findOrFail($id);
        return view('theem.pages.invoice.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoices $invoices)
    {
        $customers = Customer::all();
        $suppliers = Supplier::all();
        $products = Product::all();

        return view('theem.pages.invoice.edit', compact('invoices', 'customers', 'suppliers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoices $invoices)
    {
        $invoice = Invoices::with('items.product')->findOrFail($invoices->id);

        $request->validate([
            'status' => 'required|in:pending,paid,canceled',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:invoices_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0',
            'customer_id' => ['nullable', 'exists:customers,id'],
            'customer_name' => [
                function ($attribute, $value, $fail) use ($request) {
                    if (!$request->customer_id && (!$value || trim($value) == '')) {
                        $fail('The customer name field is required when no customer is selected.');
                    }
                },
            ],
        ]);

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $taxAmount = 0;

            $oldStatus = $invoice->status;
            $newStatus = $request->status;

            // إذا اتحولت لـ canceled → رجّع الكمية للمخزون
            if ($oldStatus != 'canceled' && $newStatus == 'canceled') {
                foreach ($invoice->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        if ($invoice->type === 'sale') {
                            $product->stock_quantity += $item->quantity;
                        } elseif ($invoice->type === 'purcash') {
                            $product->stock_quantity -= $item->quantity;
                        }
                        $product->save();
                    }
                }
            }

            // إذا اتحولت من canceled إلى paid/pending → خصم الكمية من المخزون
            if ($oldStatus == 'canceled' && $newStatus != 'canceled') {
                foreach ($invoice->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        if ($invoice->type === 'sale') {
                            if ($product->stock_quantity < $item->quantity) {
                                throw new \Exception("Product {$product->name} does not have enough stock.");
                            }
                            $product->stock_quantity -= $item->quantity;
                        } elseif ($invoice->type === 'purcash') {
                            $product->stock_quantity += $item->quantity;
                        }
                        $product->save();
                    }
                }
            }

            // تحديث تفاصيل العناصر وكميات المخزون لو الكمية اتعدلت
            foreach ($request->items as $itemData) {
                $item = Invoices_Item::findOrFail($itemData['id']);
                $product = Product::findOrFail($item->product_id);

                $oldQuantity = $item->quantity;
                $newQuantity = $itemData['quantity'];
                $quantityChanged = $oldQuantity != $newQuantity;

                // لو status مش canceled وعدلت الكمية
                if ($newStatus != 'canceled' && $quantityChanged) {
                    if ($invoice->type === 'sale') {
                        // رجّع الكمية القديمة للمخزون
                        $product->stock_quantity += $oldQuantity;
                        // تأكد أن الكمية تكفي للخصم
                        if ($product->stock_quantity < $newQuantity) {
                            throw new \Exception("Product {$product->name} does not have enough stock.");
                        }
                        // خصم الكمية الجديدة
                        $product->stock_quantity -= $newQuantity;
                    } elseif ($invoice->type === 'purcash') {
                        $product->stock_quantity -= $oldQuantity;
                        $product->stock_quantity += $newQuantity;
                    }
                    $product->save();
                }

                // Update Item Details
                $unitPrice = $itemData['unit_price'];
                $taxRate = $itemData['tax_rate'] ?? 0;

                $lineTotal = $newQuantity * $unitPrice;
                $lineTax = $lineTotal * ($taxRate / 100);

                $item->update([
                    'quantity' => $newQuantity,
                    'unit_price' => $unitPrice,
                    'tax_rate' => $taxRate,
                    'total_price' => $lineTotal + $lineTax,
                ]);

                $totalAmount += $lineTotal;
                $taxAmount += $lineTax;
            }

            $discount = $request->discount ?? 0;
            $grandTotal = $totalAmount + $taxAmount - $discount;
            if ($grandTotal < 0) $grandTotal = 0;

            $customerId = null;
            $customerName = null;

            if ($invoice->type === 'sale') {
                if ($request->customer_id) {
                    $customerId = $request->customer_id;
                    $customerName = Customer::find($customerId)?->name;
                } else {
                    $customerId = null; // Walk-in
                    $customerName = $request->customer_name;
                }
            } elseif ($invoice->type === 'purcash') {
                $customerId = null;
                $customerName = null;
            }

            // Update Invoice Main Data
            $invoice->update([
                'customer_id' => $customerId,
                'customer_name' => $customerName,
                'status' => $newStatus,
                'discount' => $discount,
                'notes' => $request->notes,
                'total_amount' => $grandTotal,
                'tax_amount' => $taxAmount,
            ]);

            DB::commit();
            return redirect()->route('invoice.index')->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating invoice: ' . $e->getMessage());
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoices $invoices)
    {
        $invoices->delete();
        return redirect()->route('invoice.index')->with('success', 'Invoice deleted successfully.');
    }



    public function print(Invoices $invoice)
    {
        $invoice->load('user', 'createdBy', 'items.product');
        return view('theem.pages.invoice.print', compact('invoice'));
    }

    public function printView(Invoices $invoice)
    {
        $invoice->load('user', 'items.product', 'customer', 'supplier');

        return view('theem.pages.invoice.print', compact('invoice'));
    }



    public function download(Invoices $invoice)
    {
        $invoice->load('user', 'items.product');
        $pdf = Pdf::loadView('theem.pages.invoice.print', compact('invoice'));

        $filename = 'invoice-' . $invoice->invoice_number . '.pdf';

        return $pdf->download($filename);
    }
}
