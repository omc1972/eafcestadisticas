<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\DivisionRival;
use App\Models\SquadBattle;
use App\Models\Rival;
use App\Models\RivalsBattle;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function store(Request $request, ?SquadBattle $squadBattle = null, ?Rival $rival = null, ?RivalsBattle $rivalsBattle = null)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'orden'  => 'required|integer|min:1',
        ]);

        if ($squadBattle) {
            $squadBattle->divisiones()->create($validated);
        } elseif ($rival) {
            $rival->divisiones()->create($validated);
        } elseif ($rivalsBattle) {
            $rivalsBattle->divisiones()->create($validated);
        }

        return back()->with('success', 'División creada correctamente');
    }

    public function update(Request $request, ?SquadBattle $squadBattle = null, ?Rival $rival = null, ?RivalsBattle $rivalsBattle = null)
    {
        $division = Division::findOrFail($request->route('division'));

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'orden'  => 'required|integer|min:1',
        ]);
        $division->update($validated);
        return back()->with('success', 'División actualizada correctamente');
    }

    public function destroy(Request $request)
    {
        $division = Division::findOrFail($request->route('division'));
        $division->delete();
        return back()->with('success', 'División eliminada correctamente');
    }

    public function addEquipo(Request $request)
    {
        $division = Division::findOrFail($request->route('division'));

        $validated = $request->validate([
            'equipo_ids'   => 'required|array|min:1',
            'equipo_ids.*' => 'exists:equipos,id',
        ]);

        $current = $division->rivales()->count();
        $adding  = count($validated['equipo_ids']);

        if ($current + $adding > 18) {
            return back()->withErrors(['equipo_ids' => "No se pueden añadir {$adding} equipos: la división solo admite " . (18 - $current) . " más."]);
        }

        $nextOrden = ($division->rivales()->max('orden') ?? 0) + 1;

        foreach ($validated['equipo_ids'] as $equipoId) {
            $division->rivales()->create([
                'equipo_id' => $equipoId,
                'orden'     => $nextOrden++,
            ]);
        }

        return back()->with('success', 'Equipos añadidos correctamente');
    }

    public function removeEquipo(Request $request)
    {
        $divisionRivalId = $request->route('divisionRival');
        $divisionRival = DivisionRival::findOrFail($divisionRivalId);
        $divisionRival->delete();
        return back()->with('success', 'Equipo eliminado correctamente');
    }

    public function updateOrden(Request $request)
    {
        $division = Division::findOrFail($request->route('division'));

        $validated = $request->validate([
            'orden'         => 'required|array',
            'orden.*.id'    => 'required|exists:divisiones_equipos,id',
            'orden.*.orden' => 'required|integer|min:1|max:18',
        ]);

        foreach ($validated['orden'] as $item) {
            DivisionRival::where('id', $item['id'])->update(['orden' => $item['orden']]);
        }

        return back()->with('success', 'Orden aleatorizado correctamente');
    }
}

