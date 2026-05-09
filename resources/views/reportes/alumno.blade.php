<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado PPE - {{ $alumno->nombre_completo }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { color: #4f46e5; margin: 0; }
        .header p { margin: 4px 0; color: #666; }
        .info { display: table; width: 100%; margin-bottom: 16px; }
        .info-row { display: table-row; }
        .info-cell { display: table-cell; padding: 4px 8px; border-bottom: 1px solid #eee; }
        .info-cell strong { color: #4f46e5; }
        .progreso-box { border: 2px solid #4f46e5; padding: 16px; margin: 16px 0; text-align: center; border-radius: 8px; }
        .progreso-box .horas { font-size: 28px; font-weight: bold; color: #4f46e5; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th { background: #f3f4f6; text-align: left; padding: 8px; border-bottom: 2px solid #ddd; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Certificado de Participación Estudiantil</h1>
        <p>{{ $institucion }}</p>
        <p>Emitido: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info">
        <div class="info-row">
            <div class="info-cell"><strong>Estudiante:</strong> {{ $alumno->nombre_completo }}</div>
            <div class="info-cell"><strong>Cédula:</strong> {{ $alumno->cedula }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell"><strong>Año:</strong> {{ $alumno->anio_bachillerato }} {{ $alumno->paralelo }}</div>
            <div class="info-cell"><strong>Email:</strong> {{ $alumno->user->email }}</div>
        </div>
    </div>

    <div class="progreso-box">
        <div class="horas">{{ number_format($horasCompletadas, 1) }} / {{ $meta }} horas</div>
        <div>Progreso: <strong>{{ $progreso }}%</strong></div>
        @if($progreso >= 100)
            <p style="color: #059669; margin-top: 8px;"><strong>HA COMPLETADO EL PROGRAMA</strong></p>
        @endif
    </div>

    <h3>Detalle de actividades</h3>
    <table>
        <thead>
            <tr><th>Fecha</th><th>Actividad</th><th>Estado</th><th style="text-align:right">Horas</th></tr>
        </thead>
        <tbody>
            @foreach($alumno->actividades as $a)
                <tr>
                    <td>{{ $a->fecha->format('d/m/Y') }}</td>
                    <td>{{ $a->titulo }}</td>
                    <td>{{ ucfirst($a->pivot->estado) }}</td>
                    <td style="text-align:right">{{ number_format($a->pivot->horas_confirmadas, 1) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente por el Sistema PPE.
    </div>
</body>
</html>
