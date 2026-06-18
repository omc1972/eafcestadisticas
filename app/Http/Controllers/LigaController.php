<?php

namespace App\Http\Controllers;

use App\Models\Liga;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LigaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ligas = Liga::all();

        return Inertia::render('Ligas/Index', [
            'ligas' => $ligas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Ligas/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'competicion_id' => 'nullable|exists:competicions,id',
            'activa' => 'boolean',
        ]);

        Liga::create($validated);

        return redirect()->route('ligas.index')->with('success', 'Liga creada con éxito');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Liga $liga)
    {
        return Inertia::render('Ligas/Edit', [
            'liga' => $liga,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Liga $liga)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'competicion_id' => 'nullable|exists:competicions,id',
            'activa' => 'boolean',
        ]);

        $liga->update($validated);

        return redirect()->route('ligas.index')->with('success', 'Liga actualizada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Liga $liga)
    {
        $liga->delete();

        return redirect()->route('ligas.index')->with('success', 'Liga eliminada con éxito');
    }
}

