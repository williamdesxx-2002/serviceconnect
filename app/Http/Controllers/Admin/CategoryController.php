<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('services')
            ->orderBy('name')
            ->paginate(20);
            
        return view('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.categories.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ]);
        
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->is_active ?? true,
            'order' => $request->order ?? 0
        ]);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }
    
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }
    
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ]);
        
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->is_active ?? $category->is_active,
            'order' => $request->order ?? $category->order
        ]);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }
    
    public function destroy(Category $category)
    {
        // Vérifier si des services sont associés à cette catégorie
        if ($category->services()->count() > 0) {
            return back()->with('error', 
                'Impossible de supprimer cette catégorie car ' . $category->services()->count() . 
                ' service(s) y sont associés.');
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
    
    public function toggle(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);
        
        return back()->with('success', 
            'Catégorie ' . ($category->is_active ? 'activée' : 'désactivée') . ' avec succès.');
    }
}
