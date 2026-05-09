<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ambito extends Model
{
    protected $table = 'ambitos';

    protected $fillable = ['nombre', 'codigo', 'descripcion', 'color', 'activo'];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    public function grupos(): HasMany
    {
        return $this->hasMany(Grupo::class);
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }
}
