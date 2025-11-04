<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::paginate(7);
        return view('theem.pages.supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('theem.pages.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:suppliers,email',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'gst_no' => 'nullable|string|max:15',
        ]);

        Supplier::create($request->all());

        return redirect()->route('supplier.index')->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     */

    public function edit(Supplier $supplier)
    {
        return view('theem.pages.supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'company_name' => 'string|max:255',
            'name' => 'string|max:255',
            'email' => 'email|max:255|unique:suppliers,email,' . $supplier->id,
            'phone' => 'string|max:15',
            'address' => 'string|max:255',
            'gst_no' => 'nullable|string|max:15',
        ]);

        $supplier->update($request->all());

        return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier deleted successfully.');
    }
    public function checkEmail(Request $request)
    {
        $email = $request->email;
        $supplierId = $request->supplier_id;

        $exists = Supplier::where('email', $email)
            ->where('id', '!=', $supplierId)  // يستبعد نفسه
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}
