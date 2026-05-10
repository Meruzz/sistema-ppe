<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $grupos = Configuracion::orderBy('grupo')->orderBy('clave')->get()->groupBy('grupo');

        return view('configuraciones.index', compact('grupos'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $clave => $valor) {
            Configuracion::set($clave, $valor ?? '');
        }

        // Toggles no enviados (checkboxes desmarcados) = 0
        $clavesBool = ['pdf_firmas', 'pdf_actividades', 'email_notif_50', 'email_notif_80', 'email_notif_100', 'email_notif_nota'];
        foreach ($clavesBool as $clave) {
            if (!isset($data[$clave])) {
                Configuracion::set($clave, '0');
            }
        }

        Configuracion::flush();

        return back()->with('success', 'Configuración guardada correctamente.');
    }
}
