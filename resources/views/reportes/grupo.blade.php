<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Grupo {{ $grupo->nombre }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { color: #4f46e5; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th { background: #f3f4f6; text-align: left; padding: 8px; border-bottom: 2px solid #ddd; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        .meta { font-size: 11px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Grupo: {{ $grupo->nombre }}</h1>
        <p class="meta">{{ $institucion }} · Año lectivo {{ $grupo->anio_lectivo }}</p>
    </div>

    <p>
        <strong>Materia:</strong> {{ $grupo->materia->nombre ?? '—' }}<br>
        <strong>Docente:</strong> {{ $grupo->docente->nombre_completo ?? '—' }}<br>
        <strong>Total alumnos:</strong> {{ $grupo->alumnos->count() }}
    </p>

    <h3>Avance por alumno (meta: {{ $meta }} h)</h3>
    <table>
        <thead>
            <tr><th>Cédula</th><th>Alumno</th><th>Año</th><th style="text-align:right">Horas</th><th style="text-align:right">Progreso</th></tr>
        </thead>
        <tbody>
            @foreach($grupo->alumnos as $a)
                <tr>
                    <td>{{ $a->cedula }}</td>
                    <td>{{ $a->nombre_completo }}</td>
                    <td>{{ $a->anio_bachillerato }} {{ $a->paralelo }}</td>
                    <td style="text-align:right">{{ number_format($a->horas_completadas, 1) }}</td>
                    <td style="text-align:right">{{ $a->progreso_horas }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="meta" style="text-align:center; margin-top:30px;">Generado el {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
