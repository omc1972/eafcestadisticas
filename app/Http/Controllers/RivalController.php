<?php

namespace App\Http\Controllers;

use App\Models\Rival;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RivalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rivales = Rival::all();

        return Inertia::render('Rivales/Index', [
            'rivales' => $rivales,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Rivales/Create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:10',
            'usuario' => 'nullable|string|max:255',
            'quimica' => 'nullable|numeric',
            'valoracion' => 'nullable|numeric',
            'tactica' => 'nullable|string|max:255',
            'fecha' => 'nullable|string',
        ]);

        Rival::create($validated);

        return redirect()->route('rivales.index')->with('success', 'Rival creado con éxito');

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
    public function edit(Rival $rival)
    {
        return Inertia::render('Rivales/Edit', [
            'rival' => $rival,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rival $rival)
    {
         $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:10',
            'usuario' => 'nullable|string|max:255',
            'quimica' => 'nullable|numeric',
            'valoracion' => 'nullable|numeric',
            'tactica' => 'nullable|string|max:255',
            'fecha' => 'nullable|string',
        ]);

        $rival->update($validated);

        return redirect()->route('rivales.index')->with('success', 'Rival modificado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rival $equipo)
    {
        $equipo->delete();

        return redirect()->route('rivales.index')->with('success', 'Rival eliminado con éxito');
    }
}
