<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnioLectivo extends Model
{
    protected $table = 'anio_lectivos';

    protected $fillable = ['nombre', 'ciclo', 'inicio', 'fin', 'activo'];

    protected $casts = [
        'inicio' => 'date',
        'fin'    => 'date',
        'activo' => 'boolean',
    ];

    public function grupos(): HasMany
    {
        return $this->hasMany(Grupo::class);
    }

    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
