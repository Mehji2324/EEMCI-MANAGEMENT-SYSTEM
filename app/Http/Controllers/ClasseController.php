<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $classes = Classe::with('filiere')
            ->when($request->filiere_id, fn($q) => $q->where('filiere_id', $request->filiere_id))
            ->get();

        return response()->json($classes);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'filiere_id'    => ['required', 'exists:filieres,id'],
            'name'          => ['required', 'string', 'max:255'],
            'level'         => ['required', 'string', 'max:100'],
            'academic_year' => ['required', 'string', 'max:20'],
            'capacity'      => ['integer', 'min:1'],
        ]);

        $classe = Classe::create($data);

        return response()->json([
            'message' => 'Classe created successfully.',
            'classe'  => $classe->load('filiere'),
        ], 201);
    }

    public function show(Classe $classe): JsonResponse
    {
        return response()->json($classe->load(['filiere', 'modules']));
    }

    public function update(Request $request, Classe $classe): JsonResponse
    {
        $data = $request->validate([
            'filiere_id'    => ['sometimes', 'exists:filieres,id'],
            'name'          => ['sometimes', 'string', 'max:255'],
            'level'         => ['sometimes', 'string', 'max:100'],
            'academic_year' => ['sometimes', 'string', 'max:20'],
            'capacity'      => ['sometimes', 'integer', 'min:1'],
        ]);

        $classe->update($data);

        return response()->json([
            'message' => 'Classe updated successfully.',
            'classe'  => $classe->load('filiere'),
        ]);
    }
}
