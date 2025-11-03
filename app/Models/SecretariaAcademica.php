<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecretariaAcademica extends Model
{
    protected $fillable = [
        'user_id',
        'apellido',
        'cif',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con las solicitudes (como secretaria que procesa)
     */
    public function solicitudesProcesadas()
    {
        return $this->hasMany(Solicitud::class, 'secretaria_id');
    }

    /**
     * Obtener el nombre completo de la secretaria académica
     */
    public function getNombreCompletoAttribute(): string
    {
        return $this->user->name . ' ' . $this->apellido;
    }
}
