<?php

namespace App\Http\Controllers\Api;

use App\Models\BankSetup;
use Illuminate\Http\Request;

class BankSetupApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $items = BankSetup::when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate($request->per_page ?? 15);
        return $this->paginatedResponse($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'routing_number' => 'nullable|string|max:50',
        ]);
        $item = BankSetup::create($validated);
        return $this->successResponse($item, 'Bank setup created successfully', 201);
    }

    public function show($id)
    {
        $item = BankSetup::find($id);
        if (!$item)
            return $this->errorResponse('Bank setup not found', 404);
        return $this->successResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = BankSetup::find($id);
        if (!$item)
            return $this->errorResponse('Bank setup not found', 404);
        $item->update($request->all());
        return $this->successResponse($item, 'Bank setup updated successfully');
    }

    public function destroy($id)
    {
        $item = BankSetup::find($id);
        if (!$item)
            return $this->errorResponse('Bank setup not found', 404);
        $item->delete();
        return $this->successResponse(null, 'Bank setup deleted successfully');
    }
}
