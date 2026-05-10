<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta PPE</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; color: #1e293b; margin: 0; padding: 0; }
        .wrap { max-width: 560px; margin: 32px auto; background: #fff; border-radius: 8px;
                border: 1px solid #e2e8f0; overflow: hidden; }
        .top-bar { background: #dc2626; height: 6px; }
        .body { padding: 32px; }
        .alerta-badge { display: inline-block; background: #fee2e2; color: #dc2626; font-weight: 700;
                        font-size: 13px; padding: 4px 14px; border-radius: 9999px; margin-bottom: 16px; }
        h2 { font-size: 20px; margin: 0 0 8px; color: #1e293b; }
        p  { font-size: 14px; line-height: 1.6; color: #475569; margin: 0 0 12px; }
        .info-box { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 6px;
                    padding: 14px 18px; margin: 16px 0; font-size: 13px; }
        .info-box strong { color: #c2410c; }
        .accion-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px;
                      padding: 14px 18px; margin: 16px 0; font-size: 13px; }
        .accion-box strong { color: #15803d; }
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 32px;
                  font-size: 11px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="top-bar"></div>
    <div class="body">
        <div class="alerta-badge">⚠️ Nota en riesgo</div>

        <h2>Atención, {{ $alumno->nombre_completo }}</h2>

        <p>Tu nota actual en el <strong>Programa de Participación Estudiantil (PPE)</strong> está por debajo del mínimo requerido para aprobar.</p>

        <div class="info-box">
            <strong>Nota actual proyectada:</strong> {{ number_format($notaActual, 2) }} / 10<br>
            <strong>Nota mínima para aprobar:</strong> {{ number_format($notaMinima, 2) }} / 10<br>
            <strong>Horas adicionales necesarias:</strong> {{ number_format($horasNecesarias, 1) }} h
        </div>

        <div class="accion-box">
            <strong>¿Qué puedes hacer?</strong><br>
            Comunícate con tu docente para conocer las próximas actividades disponibles y asegurarte de participar activamente para recuperar las horas necesarias.
        </div>

        <p>Recuerda que el PPE requiere una nota mínima de <strong>{{ number_format($notaMinima, 1) }} / 10</strong> para considerar el año lectivo aprobado en este programa.</p>
    </div>
    <div class="footer">
        Este mensaje fue generado automáticamente por el Sistema PPE.<br>
        Si tienes dudas, comunícate con tu docente o el coordinador del programa.
    </div>
</div>
</body>
</html>
