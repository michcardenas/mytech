<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'description');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Categoría creada correctamente');
    }
    // Método para mostrar formulario de edición
  public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // ← Y ESTE TAMBIÉN
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category->fill($validated);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $path = $request->file('image')->store('categories', 'public');
            $category->image = $path;
        }

        $category->save();

        return redirect()->route('categories.index')
                        ->with('success', 'Category updated successfully!');
    }
// Método para eliminar categoría
public function destroy(Category $category)
{
    try {
        // Eliminar imagen si existe
        if ($category->image && Storage::exists($category->image)) {
            Storage::delete($category->image);
        }
        
        $category->delete();
        
        return redirect()->route('categories.index')
                        ->with('success', 'Category deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->route('categories.index')
                        ->with('error', 'Error deleting category. Please try again.');
    }
}
}
