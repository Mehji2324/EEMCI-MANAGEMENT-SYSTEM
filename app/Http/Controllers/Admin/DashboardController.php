<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Classe;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\Note;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        return response()->json([
            'students' => [
                'total'     => Student::count(),
                'validated' => Student::where('status', 'validated')->count(),
                'pending'   => Student::where('status', 'pending')->count(),
            ],
            'teachers' => Teacher::count(),
            'academics' => [
                'filieres' => Filiere::count(),
                'classes'  => Classe::count(),
                'modules'  => Module::count(),
            ],
            'pending_validations' => [
                'notes'    => Note::where('status', 'pending')->count(),
                'absences' => Absence::where('status', 'pending')->count(),
            ],
            'financials' => [
                'total_paid'     => Payment::where('status', 'paid')->sum('amount'),
                'pending_amount' => Payment::where('status', 'pending')->sum('amount'),
            ]
        ]);
    }
}
