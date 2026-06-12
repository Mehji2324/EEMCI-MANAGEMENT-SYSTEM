<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $modules = Module::with(['classe', 'teacher.user'])
            ->when($request->classe_id, fn($q) => $q->where('classe_id', $request->classe_id))
            ->when($request->teacher_id, fn($q) => $q->where('teacher_id', $request->teacher_id))
            ->get();

        return response()->json($modules);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'classe_id'    => ['required', 'exists:classes,id'],
            'teacher_id'   => ['nullable', 'exists:teachers,id'],
            'name'         => ['required', 'string', 'max:255'],
            'code'         => ['required', 'string', 'unique:modules,code', 'max:50'],
            'description'  => ['nullable', 'string'],
            'credits'      => ['integer', 'min:1'],
            'semester'     => ['required', 'string', 'max:50'],
            'cc_weight'    => ['numeric', 'min:0', 'max:1'],
            'exam_weight'  => ['numeric', 'min:0', 'max:1'],
            'volume_hours' => ['integer', 'min:1'],
            'is_active'    => ['boolean'],
        ]);

        $module = Module::create($data);

        return response()->json([
            'message' => 'Module created successfully.',
            'module'  => $module->load(['classe', 'teacher']),
        ], 201);
    }

    public function show(Module $module): JsonResponse
    {
        return response()->json($module->load(['classe', 'teacher.user']));
    }

    public function update(Request $request, Module $module): JsonResponse
    {
        $data = $request->validate([
            'teacher_id'   => ['nullable', 'exists:teachers,id'],
            'name'         => ['sometimes', 'string', 'max:255'],
            'code'         => ['sometimes', 'string', 'unique:modules,code,'.$module->id, 'max:50'],
            'description'  => ['nullable', 'string'],
            'credits'      => ['sometimes', 'integer', 'min:1'],
            'semester'     => ['sometimes', 'string', 'max:50'],
            'cc_weight'    => ['sometimes', 'numeric', 'min:0', 'max:1'],
            'exam_weight'  => ['sometimes', 'numeric', 'min:0', 'max:1'],
            'volume_hours' => ['sometimes', 'integer', 'min:1'],
            'is_active'    => ['boolean'],
        ]);

        $module->update($data);

        return response()->json([
            'message' => 'Module updated successfully.',
            'module'  => $module->load(['classe', 'teacher']),
        ]);
    }
}
