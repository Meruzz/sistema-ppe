<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Convalidacion extends Model
{
    protected $table = 'convalidaciones';

    protected $fillable = [
        'alumno_id', 'tipo', 'descripcion', 'documento_referencia',
        'fecha_inicio', 'fecha_fin', 'activo', 'aprobado_por_id',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
        'activo'       => 'boolean',
    ];

    public static array $tipos = [
        'embarazo'      => 'Embarazo / maternidad',
        'scouts'        => 'Scouts activo',
        'deporte'       => 'Deportista de alto rendimiento',
        'conservatorio' => 'Conservatorio de artes',
        'otro'          => 'Otro',
    ];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function aprobadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por_id');
    }

    public function getTipoLabelAttribute(): string
    {
        return self::$tipos[$this->tipo] ?? $this->tipo;
    }
}
