<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumnos';

    protected $fillable = [
        'user_id', 'cedula', 'nombres', 'apellidos', 'fecha_nacimiento',
        'telefono', 'direccion', 'anio_bachillerato', 'paralelo',
        'representante', 'telefono_representante', 'activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'activo' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'alumno_grupo')
            ->withPivot('inscrito_en')
            ->withTimestamps();
    }

    public function actividades(): BelongsToMany
    {
        return $this->belongsToMany(Actividad::class, 'alumno_actividad')
            ->withPivot(['horas_confirmadas', 'estado', 'observaciones', 'confirmado_en'])
            ->withTimestamps();
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    public function getHorasCompletadasAttribute(): float
    {
        return (float) $this->actividades()
            ->wherePivot('estado', 'asistio')
            ->sum('horas_confirmadas');
    }

    public function getProgresoHorasAttribute(): float
    {
        $meta = config('ppe.horas_requeridas', 80);
        return min(100, round(($this->horas_completadas / $meta) * 100, 1));
    }
}
