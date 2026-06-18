<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Equipacion;

class EquipacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Equipaciones/Index',['equipaciones' => Equipacion::All()]);      
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Equipaciones/Create',[]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:128'
        ]);

        Equipacion::create([
            'nombre'=>$validated['nombre'],
        ]);

        return redirect()->route('equipaciones.index')->with('success','Equipación creada con éxito');
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
    public function edit(Equipacion $equipacion)
    {
        return Inertia::render('Equipaciones/Edit',['equipacion'=>$equipacion]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipacion $equipacion)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:128'
        ]);

        $equipacion->update([
            'nombre' => $validated['nombre']
        ]);

        return redirect()->route('equipaciones.index')->with('success','Equipación modificada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipacion $equipacion)
    {
        $equipacion->delete();
        return redirect()->route('equipaciones.index')->with('success','Equipación eliminada con éxito');
    }
}
