<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::paginate(7);
        return view('theem.pages.customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('theem.pages.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);

        Customer::create($request->all());

        return redirect()->route('customer.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */

    public function edit(Customer $customer)
    {
        return view('theem.pages.customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'string|max:15',
            'address' => 'string|max:255',
        ]);

        $customer->update($request->all());

        return redirect()->route('customer.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customer.index')->with('success', 'Customer deleted successfully.');
    }
    public function checkEmail(Request $request)
    {
        $email = $request->email;
        $customerId = $request->customer_id;

        $exists = Customer::where('email', $email)
            ->where('id', '!=', $customerId)
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}
