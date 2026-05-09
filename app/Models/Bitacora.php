<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bitacora extends Model
{
    protected $table = 'bitacoras';

    protected $fillable = [
        'alumno_id',
        'actividad_id',
        'fecha',
        'contenido',
        'aprendizajes',
        'calificacion',
        'observaciones_docente',
        'revisado_por_docente_id',
        'revisado_en',
    ];

    protected $casts = [
        'fecha'        => 'date',
        'revisado_en'  => 'datetime',
        'calificacion' => 'decimal:2',
    ];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }

    public function revisor(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'revisado_por_docente_id');
    }

    public function getRevisadaAttribute(): bool
    {
        return ! is_null($this->revisado_en);
    }
}
