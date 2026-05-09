<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos';

    protected $fillable = [
        'nombre', 'docente_id', 'ambito_id',
        'anio_lectivo', 'anio_bachillerato', 'descripcion', 'activo',
    ];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class);
    }

    public function ambito(): BelongsTo
    {
        return $this->belongsTo(Ambito::class);
    }

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(Alumno::class, 'alumno_grupo')
            ->withPivot('inscrito_en')
            ->withTimestamps();
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }
}
