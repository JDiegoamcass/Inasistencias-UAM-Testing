<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solicitud extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comentario',
        'estado',
        'evidencia',
        'fechaSolicitud',
        'fechaAusencia',
        'resolucion',
        'tipoAusencia',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
