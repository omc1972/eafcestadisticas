<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Entrenador;

class EntrenadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entrenadores = Entrenador::All();
        return Inertia::render('Entrenadores/Index', ['entrenadores' => $entrenadores]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Entrenadores/Create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'pais' => 'required|string',
            'tactica' => 'required|string',
        ]);

        Entrenador::create([
            'nombre' => $validated['nombre'],
            'pais' => $validated['pais'],
            'tactica' => $validated['tactica'],
        ]);

        return redirect()->route('entrenadores.index')->with('success', 'Entrenador creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entrenador $entrenador)
    {
        return Inertia::render('Entrenadores/Edit', [
            'entrenador' => $entrenador
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entrenador $entrenador)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'pais' => 'required|string',
            'tactica' => 'nullable|string',
        ]);

        $entrenador->update([
            'nombre' => $validated['nombre'],
            'pais' => $validated['pais'],
            'tactica' => $validated['tactica'],
        ]);

        return redirect()->route('entrenadores.index')->with('success', 'Entrenador actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entrenador $entrenador)
    {
        $entrenador->delete();
        return redirect()->route('entrenadores.index')->with('success', 'Entrenador eliminado correctamente');
    }
}
