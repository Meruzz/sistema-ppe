<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividades';

    protected $fillable = [
        'titulo', 'descripcion', 'grupo_id', 'materia_id',
        'fecha', 'hora_inicio', 'hora_fin', 'horas_asignadas',
        'lugar', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'horas_asignadas' => 'decimal:2',
        ];
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(Alumno::class, 'alumno_actividad')
            ->withPivot(['horas_confirmadas', 'estado', 'observaciones', 'confirmado_en'])
            ->withTimestamps();
    }
}
