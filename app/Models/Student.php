<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filiere_id',
        'classe_id',
        'matricule',
        'academic_email',
        'status',
        'enrollment_year',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'nationality',
        'address',
        'guardian_name',
        'guardian_phone',
        'rejection_reason',
        'validated_by',
        'validated_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'validated_at'  => 'datetime',
    ];

    // ---------- Relationships ----------
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    // ---------- Scopes ----------
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeValidated($query)
    {
        return $query->where('status', 'validated');
    }
}
