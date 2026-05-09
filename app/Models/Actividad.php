<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividades';

    protected $fillable = [
        'titulo', 'descripcion', 'grupo_id', 'ambito_id', 'fase',
        'fecha', 'hora_inicio', 'hora_fin', 'horas_asignadas',
        'lugar', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha'           => 'date',
            'horas_asignadas' => 'decimal:2',
        ];
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    public function ambito(): BelongsTo
    {
        return $this->belongsTo(Ambito::class);
    }

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(Alumno::class, 'alumno_actividad')
            ->withPivot(['horas_confirmadas', 'estado', 'observaciones', 'confirmado_en'])
            ->withTimestamps();
    }

    public function bitacoras(): HasMany
    {
        return $this->hasMany(Bitacora::class);
    }
}
