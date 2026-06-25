<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetCategory;

class AssetCategoryController extends Controller
{
    public function index()
    {
        $categories = AssetCategory::all();
        return view('admin.inventory.asset_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.inventory.asset_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:asset_categories,name|max:255',
            'status' => 'boolean',
        ]);

        AssetCategory::create($request->all());

        return redirect()->route('admin.inventory.asset-categories.index')->with('success', 'Asset Category created successfully.');
    }

    public function edit($id)
    {
        $category = AssetCategory::findOrFail($id);
        return view('admin.inventory.asset_categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = AssetCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:asset_categories,name,' . $category->id,
            'status' => 'boolean',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.inventory.asset-categories.index')->with('success', 'Asset Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = AssetCategory::findOrFail($id);
        
        if($category->assets()->count() > 0) {
            return redirect()->route('admin.inventory.asset-categories.index')->with('error', 'Cannot delete category because it has associated assets.');
        }

        $category->delete();

        return redirect()->route('admin.inventory.asset-categories.index')->with('success', 'Asset Category deleted successfully.');
    }
}
