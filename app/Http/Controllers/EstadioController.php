<?php

namespace App\Http\Controllers;

use App\Models\Estadio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EstadioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Estadios/Index', [
            'estadios' => Estadio::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Estadios/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Estadio::create($validated);

        return redirect()->route('estadios.index')
            ->with('success', 'Estadio creado con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Estadio $estadio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estadio $estadio)
    {
        return Inertia::render('Estadios/Edit', [
            'estadio' => $estadio
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estadio $estadio)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $estadio->update($validated);

        return redirect()->route('estadios.index')
            ->with('success', 'Estadio actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estadio $estadio)
    {
        $estadio->delete();

        return redirect()->route('estadios.index')
            ->with('success', 'Estadio eliminado con éxito');
    }
}
