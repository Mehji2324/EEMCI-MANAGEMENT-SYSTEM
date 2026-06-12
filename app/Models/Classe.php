<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'filiere_id',
        'name',
        'level',
        'academic_year',
        'capacity',
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}
