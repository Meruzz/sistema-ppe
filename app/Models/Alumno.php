<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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
            'activo'           => 'boolean',
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

    public function bitacoras(): HasMany
    {
        return $this->hasMany(Bitacora::class);
    }

    public function convalidaciones(): HasMany
    {
        return $this->hasMany(Convalidacion::class);
    }

    public function tieneConvalidacion(): bool
    {
        return $this->convalidaciones()->where('activo', true)->exists();
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    private function horasPorAnio(string $anio): float
    {
        return (float) DB::table('alumno_actividad')
            ->join('actividades', 'actividades.id', '=', 'alumno_actividad.actividad_id')
            ->join('grupos', 'grupos.id', '=', 'actividades.grupo_id')
            ->where('alumno_actividad.alumno_id', $this->id)
            ->where('alumno_actividad.estado', 'asistio')
            ->where('grupos.anio_bachillerato', $anio)
            ->sum('alumno_actividad.horas_confirmadas');
    }

    public function getHorasCompletadas1roAttribute(): float
    {
        return $this->horasPorAnio('1ro');
    }

    public function getHorasCompletadas2doAttribute(): float
    {
        return $this->horasPorAnio('2do');
    }

    public function getHorasCompletadasAttribute(): float
    {
        return $this->horas_completadas_1ro + $this->horas_completadas_2do;
    }

    public function getProgresoHoras1roAttribute(): float
    {
        $meta = config('ppe.horas_por_anio', 80);
        return min(100, round(($this->horas_completadas_1ro / $meta) * 100, 1));
    }

    public function getProgresoHoras2doAttribute(): float
    {
        $meta = config('ppe.horas_por_anio', 80);
        return min(100, round(($this->horas_completadas_2do / $meta) * 100, 1));
    }

    public function getProgresoHorasAttribute(): float
    {
        return round(($this->progreso_horas_1ro + $this->progreso_horas_2do) / 2, 1);
    }

    public function getCalificacion1roAttribute(): float
    {
        $meta = config('ppe.horas_por_anio', 80);
        return min(10, round(($this->horas_completadas_1ro / $meta) * 10, 2));
    }

    public function getCalificacion2doAttribute(): float
    {
        $meta = config('ppe.horas_por_anio', 80);
        return min(10, round(($this->horas_completadas_2do / $meta) * 10, 2));
    }

    public function getPorcentajeFaltasAttribute(): float
    {
        $total = DB::table('alumno_actividad')
            ->where('alumno_id', $this->id)
            ->whereIn('estado', ['asistio', 'falto', 'justificado'])
            ->count();

        if ($total === 0) return 0;

        $faltas = DB::table('alumno_actividad')
            ->where('alumno_id', $this->id)
            ->where('estado', 'falto')
            ->count();

        return round(($faltas / $total) * 100, 1);
    }

    public function getEnRiesgoAttribute(): bool
    {
        $notaMinima = config('ppe.nota_minima', 7.0);
        $maxFaltas  = config('ppe.max_faltas_pct', 10);

        return $this->calificacion_1ro < $notaMinima
            || $this->calificacion_2do < $notaMinima
            || $this->porcentaje_faltas > $maxFaltas;
    }
}
