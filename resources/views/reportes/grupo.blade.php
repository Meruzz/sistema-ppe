<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Grupo {{ $grupo->nombre }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; color: #1e293b; font-size: 11px; line-height: 1.5; }

        .page-border { border: 3px solid #4f46e5; padding: 24px; }

        .header { text-align: center; padding-bottom: 14px; margin-bottom: 18px; border-bottom: 1px solid #c7d2fe; }
        .header .inst { font-size: 13px; font-weight: bold; color: #1e293b; }
        .header .titulo { font-size: 15px; font-weight: bold; color: #4f46e5; margin: 6px 0 4px; }
        .header .subtitulo { font-size: 10px; color: #64748b; }

        .meta-grid { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .meta-grid td { padding: 5px 8px; border: 1px solid #e2e8f0; font-size: 10.5px; }
        .meta-grid td.label { font-weight: bold; color: #4f46e5; background: #f5f3ff; width: 28%; }

        .resumen-row { display: table; width: 100%; margin-bottom: 16px; }
        .resumen-box { display: table-cell; border: 1px solid #e2e8f0; border-radius: 4px;
                       padding: 10px 14px; text-align: center; width: 25%; }
        .resumen-box .num { font-size: 22px; font-weight: bold; color: #4f46e5; }
        .resumen-box .lbl { font-size: 9.5px; color: #64748b; }

        table.alumnos { width: 100%; border-collapse: collapse; font-size: 10px; margin-top: 10px; }
        table.alumnos th { background: #f1f5f9; text-align: left; padding: 6px 8px;
                           border-bottom: 2px solid #cbd5e1; color: #475569; font-size: 9.5px; text-transform: uppercase; }
        table.alumnos td { padding: 5px 8px; border-bottom: 1px solid #f1f5f9; }

        .badge { display: inline-block; padding: 1px 7px; border-radius: 9999px; font-size: 9px; font-weight: 600; }
        .badge-ok   { background: #d1fae5; color: #065f46; }
        .badge-warn { background: #fef3c7; color: #92400e; }
        .badge-bad  { background: #fee2e2; color: #991b1b; }

        .firmas { margin-top: 40px; display: table; width: 100%; }
        .firma-col { display: table-cell; width: 50%; text-align: center; padding: 0 24px; }
        .firma-linea { border-top: 1px solid #94a3b8; padding-top: 6px; margin-top: 40px; font-size: 10px; color: #475569; }

        .footer { text-align: center; font-size: 9px; color: #94a3b8; margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 8px; }
    </style>
</head>
<body>
<div class="page-border">

    {{-- ── Encabezado ──────────────────────────────── --}}
    <div class="header">
        <div class="inst">{{ $cfg['inst_nombre'] }} · {{ $cfg['inst_ciudad'] }}</div>
        <div class="titulo">Reporte de Grupo: {{ $grupo->nombre }}</div>
        <div class="subtitulo">
            Programa de Participación Estudiantil (PPE) ·
            Año lectivo {{ $grupo->anioLectivo?->nombre ?? '—' }}
        </div>
        <div class="subtitulo" style="margin-top:4px;">Emitido el {{ now()->format('d/m/Y') }}</div>
    </div>

    {{-- ── Datos del grupo ─────────────────────────── --}}
    <table class="meta-grid">
        <tr>
            <td class="label">Ámbito PPE</td>
            <td>{{ $grupo->ambito?->nombre ?? '—' }}</td>
            <td class="label">Docente</td>
            <td>{{ $grupo->docente?->nombre_completo ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Año bachillerato</td>
            <td>{{ $grupo->anio_bachillerato }} Bachillerato</td>
            <td class="label">Meta de horas</td>
            <td>{{ $meta }} h por estudiante</td>
        </tr>
    </table>

    {{-- ── Resumen estadístico ──────────────────────── --}}
    @php
        $notaMin  = config('ppe.nota_minima', 7.0);
        $aprobados   = $grupo->alumnos->filter(fn($a) => $a->horas_completadas >= $meta)->count();
        $enRiesgo    = $grupo->alumnos->filter(fn($a) => $a->horas_completadas < $meta * 0.7)->count();
        $horasPromedio = $grupo->alumnos->count()
            ? round($grupo->alumnos->sum(fn($a) => $a->horas_completadas) / $grupo->alumnos->count(), 1)
            : 0;
    @endphp
    <div class="resumen-row">
        <div class="resumen-box">
            <div class="num">{{ $grupo->alumnos->count() }}</div>
            <div class="lbl">Alumnos</div>
        </div>
        <div class="resumen-box" style="border-left:none;">
            <div class="num" style="color:#059669;">{{ $aprobados }}</div>
            <div class="lbl">Completaron (≥ {{ $meta }} h)</div>
        </div>
        <div class="resumen-box" style="border-left:none;">
            <div class="num" style="color:#dc2626;">{{ $enRiesgo }}</div>
            <div class="lbl">En riesgo (< 70%)</div>
        </div>
        <div class="resumen-box" style="border-left:none;">
            <div class="num">{{ $horasPromedio }}</div>
            <div class="lbl">Promedio horas</div>
        </div>
    </div>

    {{-- ── Tabla de alumnos ─────────────────────────── --}}
    <h3 style="font-size:11px; color:#4f46e5; margin-bottom:6px;">Avance por estudiante</h3>
    <table class="alumnos">
        <thead>
            <tr>
                <th>#</th>
                <th>Cédula</th>
                <th>Estudiante</th>
                <th>Año</th>
                <th style="text-align:right">Horas</th>
                <th style="text-align:right">Progreso</th>
                <th style="text-align:right">Nota</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grupo->alumnos->sortBy('apellidos') as $idx => $a)
            @php
                $horas  = $a->horas_completadas;
                $pct    = $a->progreso_horas;
                $nota   = $meta > 0 ? round(min($horas / $meta, 1) * 10, 2) : 0;
            @endphp
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $a->cedula }}</td>
                <td>{{ $a->nombre_completo }}</td>
                <td>{{ $a->anio_bachillerato }} {{ $a->paralelo }}</td>
                <td style="text-align:right">{{ number_format($horas, 1) }}</td>
                <td style="text-align:right">{{ $pct }}%</td>
                <td style="text-align:right">{{ number_format($nota, 2) }}</td>
                <td>
                    @if($horas >= $meta)
                        <span class="badge badge-ok">Completó</span>
                    @elseif($pct >= 70)
                        <span class="badge badge-warn">En curso</span>
                    @else
                        <span class="badge badge-bad">En riesgo</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ── Firmas ───────────────────────────────────── --}}
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
