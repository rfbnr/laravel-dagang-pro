<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate(7);
        return view('theem.pages.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $existingCodes = Category::pluck('code')->toArray();
        return view('theem.pages.category.create', compact('categories', 'existingCodes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:products,product_code',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,g',
            'description' => 'nullable|string',
        ]);

        $category = new Category();
        $category->name = $validate['name'];
        $category->code = $validate['code'];
        $category->description = $validate['description'];
        $category->image = $request->file('image') ? $request->file('image')->store('categories', 'public') : null;
        $category->save();

        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('theem.pages.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('theem.pages.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validate = $request->validate([
            'name' => 'string|max:255',
            'code' => 'string|max:100|unique:products,product_code,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,g',
            'description' => 'nullable|string',
        ]);

        $category->name = $validate['name'];
        $category->code = $validate['code'];
        $category->description = $validate['description'];
        if ($request->file('image')) {
            $category->image = $request->file('image')->store('categories', 'public');
        }
        $category->save();

        return redirect()->route('category.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
    }
}
