<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progreso PPE</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; color: #1e293b; margin: 0; padding: 0; }
        .wrap { max-width: 560px; margin: 32px auto; background: #fff; border-radius: 8px;
                border: 1px solid #e2e8f0; overflow: hidden; }
        .top-bar { background: #4f46e5; height: 6px; }
        .body { padding: 32px; }
        .hito-badge { display: inline-block; background: #ede9fe; color: #4f46e5; font-weight: 700;
                      font-size: 13px; padding: 4px 14px; border-radius: 9999px; margin-bottom: 16px; }
        h2 { font-size: 20px; margin: 0 0 8px; color: #1e293b; }
        p  { font-size: 14px; line-height: 1.6; color: #475569; margin: 0 0 12px; }
        .prog-bar-bg { background: #e2e8f0; border-radius: 6px; height: 14px; margin: 12px 0; }
        .prog-bar-fill { background: #4f46e5; border-radius: 6px; height: 14px; }
        .info-box { background: #f5f3ff; border: 1px solid #c4b5fd; border-radius: 6px;
                    padding: 14px 18px; margin: 16px 0; font-size: 13px; }
        .info-box strong { color: #4f46e5; }
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 32px;
                  font-size: 11px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="top-bar"></div>
    <div class="body">
        @if($porcentaje >= 100)
            <div class="hito-badge">🎉 ¡Programa completado!</div>
        @elseif($porcentaje >= 80)
            <div class="hito-badge">🚀 80 % alcanzado</div>
        @else
            <div class="hito-badge">⭐ 50 % alcanzado</div>
        @endif

        <h2>Hola, {{ $alumno->nombre_completo }}</h2>

        @if($porcentaje >= 100)
            <p>¡Felicitaciones! Has completado el <strong>Programa de Participación Estudiantil (PPE)</strong>. Tu esfuerzo y dedicación son un ejemplo para toda la comunidad educativa.</p>
        @else
            <p>Has alcanzado el <strong>{{ $porcentaje }}%</strong> de las horas requeridas del <strong>Programa de Participación Estudiantil (PPE)</strong>. ¡Sigue así!</p>
        @endif

        <div class="info-box">
            <strong>Horas completadas:</strong> {{ number_format($horasCompletadas, 1) }} / {{ $meta }} h<br>
            <strong>Progreso:</strong> {{ $porcentaje }}%
            @php $nota = $meta > 0 ? min(10, round($horasCompletadas / $meta * 10, 2)) : 0; @endphp
            <br><strong>Nota proyectada:</strong> {{ number_format($nota, 2) }} / 10
        </div>

        <div class="prog-bar-bg">
            <div class="prog-bar-fill" style="width: {{ min($porcentaje, 100) }}%;"></div>
        </div>

        @if($porcentaje < 100)
            <p>Te faltan <strong>{{ number_format($meta - $horasCompletadas, 1) }} horas</strong> para completar el programa. Continúa participando en las actividades programadas por tu docente.</p>
        @endif
    </div>
    <div class="footer">
        Este mensaje fue generado automáticamente por el Sistema PPE.<br>
        Si tienes dudas, comunícate con tu docente o el coordinador del programa.
    </div>
</div>
</body>
</html>
