<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table       = 'configuraciones';
    protected $primaryKey  = 'clave';
    public    $incrementing = false;
    protected $keyType     = 'string';

    protected $fillable = ['clave', 'valor', 'descripcion', 'grupo'];

    private static array $cache = [];

    public static function get(string $clave, mixed $default = null): mixed
    {
        if (array_key_exists($clave, static::$cache)) {
            return static::$cache[$clave];
        }

        $row = static::find($clave);
        $value = $row?->valor ?? $default;
        static::$cache[$clave] = $value;

        return $value;
    }

    public static function set(string $clave, mixed $valor): void
    {
        static::updateOrCreate(['clave' => $clave], ['valor' => $valor]);
        static::$cache[$clave] = $valor;
    }

    public static function flush(): void
    {
        static::$cache = [];
    }
}
