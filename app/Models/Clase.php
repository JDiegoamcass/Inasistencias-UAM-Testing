<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo',
        'profesor_id',
        'horario',
    ];

    public function profesor()
    {
        return $this->belongsTo(Profesor::class);
    }
}
