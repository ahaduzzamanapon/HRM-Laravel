<?php

namespace App\Http\Controllers\Api;

use App\Models\TaxSetup;
use Illuminate\Http\Request;

class TaxSetupApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = TaxSetup::when($request->search, fn($q) => $q->where('title', 'like', '%' . $request->search . '%'))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0',
        ]);
        $item = TaxSetup::create($validated);
        return $this->successResponse($item, 'Tax setup created successfully', 201);
    }

    public function show($id)
    {
        $item = TaxSetup::find($id);
        if (!$item)
            return $this->errorResponse('Tax setup not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = TaxSetup::find($id);
        if (!$item)
            return $this->errorResponse('Tax setup not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Tax setup updated successfully');
    }

    public function destroy($id)
    {
        $item = TaxSetup::find($id);
        if (!$item)
            return $this->errorResponse('Tax setup not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Tax setup deleted successfully');
    }
}
