<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'module_id',
        'cc_note',
        'exam_note',
        'average',
        'status',
        'submitted_by',
        'validated_by',
        'validated_at',
        'comment',
    ];

    protected $casts = [
        'cc_note'      => 'float',
        'exam_note'    => 'float',
        'average'      => 'float',
        'validated_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Compute average using the module's CC/Exam weighting.
     */
    public function computeAverage(): float
    {
        $module = $this->module;
        $cc     = $this->cc_note ?? 0;
        $exam   = $this->exam_note ?? 0;

        return round(($cc * $module->cc_weight) + ($exam * $module->exam_weight), 2);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeValidated($query)
    {
        return $query->where('status', 'validated');
    }
}
