<?php

namespace App\Http\Controllers;

use App\Http\Requests\AmbitoRequest;
use App\Models\Ambito;
use Illuminate\Http\Request;

class AmbitoController extends Controller
{
    public function index(Request $request)
    {
        $ambitos = Ambito::when($request->q, fn ($q, $t) => $q->where('nombre', 'like', "%{$t}%"))
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('ambitos.index', compact('ambitos'));
    }

    public function create()
    {
        return view('ambitos.create');
    }

    public function store(AmbitoRequest $request)
    {
        Ambito::create($request->validated());
        return redirect()->route('ambitos.index')->with('success', 'Ámbito creado.');
    }

    public function edit(Ambito $ambito)
    {
        return view('ambitos.edit', compact('ambito'));
    }

    public function update(AmbitoRequest $request, Ambito $ambito)
    {
        $ambito->update($request->validated());
        return redirect()->route('ambitos.index')->with('success', 'Ámbito actualizado.');
    }

    public function destroy(Ambito $ambito)
    {
        $ambito->delete();
        return redirect()->route('ambitos.index')->with('success', 'Ámbito eliminado.');
    }
}
