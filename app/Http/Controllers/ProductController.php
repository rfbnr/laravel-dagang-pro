<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $products = Product::with('category', 'supplier')->paginate(7);
        return view('theem.pages.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        $existingCodes = Product::pluck('product_code')->toArray(); // الأكواد الموجودة بالفعل
        return view('theem.pages.product.create', compact('categories', 'suppliers', 'existingCodes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'product_code' => 'required|string|max:100|unique:products,product_code',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'sale_price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,g',
            'description' => 'nullable|string',
        ]);

        $product = new Product();
        $product->name = $validate['name'];
        $product->product_code = $validate['product_code'];
        $product->category_id = $validate['category_id'];
        $product->supplier_id = $validate['supplier_id'] ?? null;
        $product->sale_price = $validate['sale_price'];
        $product->cost_price = $validate['cost_price'];
        $product->stock_quantity = $validate['stock_quantity'];
        $product->unit = $validate['unit'];
        $product->description = $validate['description'];
        $product->image = $request->file('image') ? $request->file('image')->store('products', 'public') : null;
        $product->save();
        return redirect()->route('product.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category', 'supplier');
        return view('theem.pages.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        $existingCodes = Product::where('id', '!=', $product->id)->pluck('product_code')->toArray();
        return view('theem.pages.product.edit', compact('product', 'categories', 'suppliers', 'existingCodes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_code' => 'required|string|max:100|unique:products,product_code,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'sale_price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        // Check if coming from low-stock page
        if ($request->has('from') && $request->from == 'low-stock') {
            return redirect()->route('product.lowStock')->with('success', 'Product updated successfully.');
        }

        return redirect()->route('product.index')->with('success', 'Product updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        if ($product->image && file_exists(public_path('storage/products/' . $product->image))) {
            unlink(public_path('storage/products/' . $product->image));
        }
        $product->delete();

        if ($request->has('from') && $request->from == 'low-stock') {
            return redirect()->route('product.lowStock')->with('success', 'Product deleted successfully.');
        }

        return redirect()->route('product.index')->with('success', 'Product deleted successfully.');
    }


    public function lowStock()
    {
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'reorder_level')
            ->with('category', 'supplier')
            ->paginate(10);

        return view('theem.pages.product.low-stock', compact('lowStockProducts'));
    }

    public function checkCode(Request $request)
    {
        $code = $request->code;
        $exists = Product::where('product_code', $code)->exists();

        return response()->json(['exists' => $exists]);
    }


    public function getStockQuantity($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }

        return response()->json(['status' => 'success', 'stock_quantity' => $product->stock_quantity]);
    }
}
