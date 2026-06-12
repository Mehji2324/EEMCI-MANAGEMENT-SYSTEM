<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'classe_id',
        'teacher_id',
        'name',
        'code',
        'description',
        'credits',
        'semester',
        'cc_weight',
        'exam_weight',
        'volume_hours',
        'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'cc_weight'   => 'float',
        'exam_weight' => 'float',
    ];

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }
}
