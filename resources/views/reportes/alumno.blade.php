<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado PPE — {{ $alumno->nombre_completo }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; color: #1e293b; font-size: 11px; line-height: 1.5; }

        .page-border { border: 3px solid #4f46e5; padding: 24px; min-height: 100%; }

        /* Header */
        .header { text-align: center; padding-bottom: 14px; margin-bottom: 18px; border-bottom: 1px solid #c7d2fe; }
        .header .inst { font-size: 13px; font-weight: bold; color: #1e293b; }
        .header .titulo { font-size: 16px; font-weight: bold; color: #4f46e5; margin: 6px 0 4px; letter-spacing: 0.3px; }
        .header .subtitulo { font-size: 10px; color: #64748b; }

        /* Info table */
        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .info-grid td { padding: 5px 8px; border: 1px solid #e2e8f0; font-size: 10.5px; }
        .info-grid td.label { font-weight: bold; color: #4f46e5; background: #f5f3ff; width: 38%; }

        /* Calificación box */
        .nota-box { border: 2px solid #4f46e5; background: #f5f3ff; border-radius: 6px;
                    padding: 14px; margin: 16px 0; text-align: center; }
        .nota-box .nota-num { font-size: 36px; font-weight: bold; color: #4f46e5; }
        .nota-box .nota-label { font-size: 10px; color: #6366f1; margin-top: 2px; }
        .nota-box .nota-estado { font-size: 11px; font-weight: bold; margin-top: 6px; }
        .aprobado { color: #059669; }
        .reprobado { color: #dc2626; }

        /* Barras de progreso */
        .progreso-section { margin-bottom: 16px; }
        .progreso-row { margin-bottom: 8px; }
        .progreso-row .prog-label { font-size: 10px; color: #475569; margin-bottom: 3px; }
        .prog-bar-bg { background: #e2e8f0; border-radius: 4px; height: 10px; width: 100%; }
        .prog-bar-fill { background: #4f46e5; border-radius: 4px; height: 10px; }

        /* Actividades */
        table.acts { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10px; }
        table.acts th { background: #f1f5f9; text-align: left; padding: 6px 8px;
                        border-bottom: 2px solid #cbd5e1; color: #475569; font-size: 9.5px; text-transform: uppercase; }
        table.acts td { padding: 5px 8px; border-bottom: 1px solid #f1f5f9; }
        .badge { display: inline-block; padding: 1px 7px; border-radius: 9999px; font-size: 9px; font-weight: 600; }
        .badge-ok   { background: #d1fae5; color: #065f46; }
        .badge-pend { background: #fef3c7; color: #92400e; }
        .badge-fase { background: #e0e7ff; color: #3730a3; }

        /* Firmas */
        .firmas { margin-top: 40px; display: table; width: 100%; }
        .firma-col { display: table-cell; width: 50%; text-align: center; padding: 0 24px; }
        .firma-linea { border-top: 1px solid #94a3b8; padding-top: 6px; margin-top: 40px; font-size: 10px; color: #475569; }

        /* Footer */
        .footer { text-align: center; font-size: 9px; color: #94a3b8; margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 8px; }
    </style>
</head>
<body>
<div class="page-border">

    {{-- ── Encabezado ─────────────────────────────────── --}}
    <div class="header">
        <div class="inst">{{ $cfg['inst_nombre'] }} · {{ $cfg['inst_ciudad'] }}</div>
        <div class="titulo">Certificado de Participación Estudiantil</div>
        <div class="subtitulo">Programa de Participación Estudiantil (PPE) — Ministerio de Educación del Ecuador</div>
        <div class="subtitulo" style="margin-top:4px;">Emitido el {{ now()->format('d/m/Y') }}</div>
    </div>

    {{-- ── Datos del estudiante ───────────────────────── --}}
    <table class="info-grid">
        <tr>
            <td class="label">Estudiante</td>
            <td>{{ $alumno->nombre_completo }}</td>
            <td class="label">Cédula</td>
            <td>{{ $alumno->cedula }}</td>
        </tr>
        <tr>
            <td class="label">Año / Paralelo</td>
            <td>{{ $alumno->anio_bachillerato }} Bachillerato · Paralelo {{ $alumno->paralelo ?? '—' }}</td>
            <td class="label">Correo</td>
            <td>{{ $alumno->user->email }}</td>
        </tr>
        @php $grupo = $alumno->grupos->first(); @endphp
        @if($grupo)
        <tr>
            <td class="label">Grupo PPE</td>
            <td>{{ $grupo->nombre }}</td>
            <td class="label">Ámbito</td>
            <td>{{ $grupo->ambito?->nombre ?? '—' }}</td>
        </tr>
        @endif
    </table>

    {{-- ── Calificación ────────────────────────────────── --}}
    @php $aprobado = $nota >= config('ppe.nota_minima', 7.0); @endphp
    <div class="nota-box">
        <div class="nota-num">{{ number_format($nota, 2) }}</div>
        <div class="nota-label">Nota PPE (escala 0 – 10)</div>
        <div class="nota-estado {{ $aprobado ? 'aprobado' : 'reprobado' }}">
            {{ $aprobado ? '✓ APROBADO' : '✗ EN RIESGO (mínimo ' . config('ppe.nota_minima', 7.0) . ')' }}
        </div>
    </div>

    {{-- ── Progreso de horas ───────────────────────────── --}}
    <div class="progreso-section">
        @php $pct = min($progreso, 100); @endphp
        <div class="progreso-row">
            <div class="prog-label">
                Horas completadas: <strong>{{ number_format($horasCompletadas, 1) }} / {{ $meta }} h</strong>
                ({{ $progreso }}%)
            </div>
            <div class="prog-bar-bg">
                <div class="prog-bar-fill" style="width: {{ $pct }}%;"></div>
            </div>
        </div>
    </div>

    {{-- ── Actividades ─────────────────────────────────── --}}
    @if($cfg['pdf_actividades'] && $alumno->actividades->isNotEmpty())
    <h3 style="font-size:11px; color:#4f46e5; margin-bottom:6px; margin-top:4px;">Detalle de actividades</h3>
    <table class="acts">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Actividad</th>
                <th>Grupo</th>
                <th>Fase</th>
                <th>Estado</th>
                <th style="text-align:right">Horas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alumno->actividades->sortByDesc('fecha') as $a)
            <tr>
                <td>{{ $a->fecha->format('d/m/Y') }}</td>
                <td>{{ $a->titulo }}</td>
                <td>{{ $a->grupo?->nombre ?? '—' }}</td>
                <td><span class="badge badge-fase">{{ config('ppe.fases.'.$a->fase, $a->fase) }}</span></td>
                <td>
                    @if($a->pivot->estado === 'asistio')
                        <span class="badge badge-ok">Asistió</span>
                    @else
                        <span class="badge badge-pend">{{ ucfirst($a->pivot->estado) }}</span>
                    @endif
                </td>
                <td style="text-align:right">{{ number_format($a->pivot->horas_confirmadas, 1) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- ── Firmas ──────────────────────────────────────── --}}
    @if($cfg['pdf_firmas'])
    <div class="firmas">
        <div class="firma-col">
            <div class="firma-linea">
                <strong>{{ $cfg['inst_director'] }}</strong><br>
                Rector/a
            </div>
        </div>
        <div class="firma-col">
            <div class="firma-linea">
                <strong>{{ $cfg['inst_coordinador'] }}</strong><br>
                Coordinador/a PPE
            </div>
        </div>
    </div>
    @endif

    <div class="footer">{{ $cfg['pdf_pie'] }}</div>

</div>
</body>
</html>
