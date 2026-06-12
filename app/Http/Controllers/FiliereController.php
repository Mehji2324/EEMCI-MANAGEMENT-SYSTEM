<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filieres = Filiere::when($request->search, function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('code', 'like', "%{$request->search}%");
        })->get();

        return response()->json($filieres);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'code'        => ['required', 'string', 'unique:filieres,code', 'max:50'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        $filiere = Filiere::create($data);

        return response()->json([
            'message' => 'Filiere created successfully.',
            'filiere' => $filiere,
        ], 201);
    }

    public function show(Filiere $filiere): JsonResponse
    {
        return response()->json($filiere->load('classes'));
    }

    public function update(Request $request, Filiere $filiere): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['sometimes', 'string', 'max:255'],
            'code'        => ['sometimes', 'string', 'unique:filieres,code,'.$filiere->id, 'max:50'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        $filiere->update($data);

        return response()->json([
            'message' => 'Filiere updated successfully.',
            'filiere' => $filiere,
        ]);
    }

    public function destroy(Filiere $filiere): JsonResponse
    {
        if ($filiere->classes()->exists() || $filiere->students()->exists()) {
            return response()->json([
                'message' => 'Cannot delete filiere. It has associated classes or students.',
            ], 422);
        }

        $filiere->delete();

        return response()->json(['message' => 'Filiere deleted successfully.']);
    }
}
