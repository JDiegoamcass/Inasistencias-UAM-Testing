<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profesor extends Model
{
    protected $table = 'profesores';

    protected $fillable = [
        'user_id',
        'apellido',
        'cif',
        'facultad',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function solicitudesAprobadas(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'profesor_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->user->name . ' ' . $this->apellido;
    }
}
