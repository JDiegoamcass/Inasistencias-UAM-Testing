<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estudiante extends Model
{
    protected $fillable = [
        'user_id',
        'apellido',
        'cif',
        'carrera',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con las solicitudes
     */
    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }

    /**
     * Obtener el nombre completo del estudiante
     */
    public function getNombreCompletoAttribute(): string
    {
        return $this->user->name . ' ' . $this->apellido;
    }
}
