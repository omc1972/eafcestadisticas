<?php

namespace App\Http\Controllers;

use App\Models\Campeonato;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CampeonatoController extends Controller
{
    public function index()
    {
        $campeonatos = Campeonato::all();
        return Inertia::render('Campeonatos/Index', [
            'campeonatos' => $campeonatos
        ]);
    }

    public function create()
    {
        return Inertia::render('Campeonatos/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:liga,mixto,eliminatorias',
        ]);
        Campeonato::create($validated);
        return redirect()->route('campeonatos.index')->with('success', 'Campeonato creado correctamente');
    }

    public function edit(Campeonato $campeonato)
    {
        return Inertia::render('Campeonatos/Edit', [
            'campeonato' => $campeonato,
            'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : []
        ]);
    }

    public function update(Request $request, Campeonato $campeonato)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:liga,mixto,eliminatorias',
        ]);
        $campeonato->update($validated);
        return redirect()->route('campeonatos.index')->with('success', 'Campeonato actualizado correctamente');
    }

    public function destroy(Campeonato $campeonato)
    {
        $campeonato->delete();
        return redirect()->route('campeonatos.index')->with('success', 'Campeonato eliminado correctamente');
    }
}
