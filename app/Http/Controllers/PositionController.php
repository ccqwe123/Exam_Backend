<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $positions = Position::with('reportsTo')
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('name')
            ->get();
    
            return response()->json($positions);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|unique:positions',
                'reports_to' => 'nullable|exists:positions,id'
            ]);

            $position = Position::create($validated);

            return response()->json(['message'=>'Position Successfully Created!', 'data'=>$position], 201);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $position = Position::with('reportsTo')->findOrFail($id);
            return response()->json($position);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Position not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|unique:positions,name,' . $id,
                'reports_to' => 'nullable|exists:positions,id'
            ]);
    
            $position = Position::findOrFail($id);
            $position->update($validated);
    
            return response()->json(['message'=>'Position Successfully updated!', 'data'=>$position]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Position not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $position = Position::findOrFail($id);
            $position->delete();
    
            return response()->json(['message' => 'Position deleted']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Position not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
