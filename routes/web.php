 <?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EntrenadorController;
use App\Http\Controllers\EquipacionController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\EstadioController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\PlantillaController;
use App\Http\Controllers\RivalController;
use App\Http\Controllers\TemporadaController;
use App\Http\Controllers\LigaController;
use App\Http\Controllers\TemporadaLigaController;
use App\Http\Controllers\LigaEstadisticasController;
use App\Http\Controllers\CampeonatoController;
use App\Http\Controllers\CompeticionController;
use App\Http\Controllers\SquadBattleController;
use App\Http\Controllers\RivalsBattleController;
use App\Http\Controllers\DivisionController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    Route::resource('jugadores', JugadorController::class)
    ->parameters([
        'jugadores' => 'jugador' 
    ]);
    Route::resource('entrenadores', EntrenadorController::class)
     ->parameters([
         'entrenadores' => 'entrenador'
     ]);

    Route::resource('estadios', EstadioController::class);
    Route::resource('equipaciones', EquipacionController::class)
     ->parameters ([
        'equipaciones' => 'equipacion'
     ]);
    Route::resource('temporadas', TemporadaController::class);
    Route::resource('partidos', PartidoController::class);
    Route::resource('equipos', EquipoController::class);
    Route::resource('rivales', RivalController::class)
    ->parameters([
        'rivales' => 'rival'
    ]);
    Route::get('/ajax/plantillas', [PlantillaController::class, 'getPlantillas'])->name('plantillas.ajax');
    Route::get('/ajax/partidos/options', [PartidoController::class, 'options'])->name('partidos.options');
    Route::resource('plantillas', PlantillaController::class);
    Route::resource('ligas', LigaController::class);
    Route::resource('campeonatos', CampeonatoController::class);
    Route::resource('competiciones', CompeticionController::class);
    
    // Rutas para gestión de orden de ligas en temporadas
    Route::get('/temporadas/{temporada}/gestion-ligas', [TemporadaLigaController::class, 'index'])->name('temporadas.gestionLigas');
    Route::post('/temporadas/{temporada}/gestion-ligas/auto-generar', [TemporadaLigaController::class, 'autoGenerarPlantillas'])->name('temporadas.autoGenerarPlantillas');
    Route::post('/temporadas/{temporada}/gestion-ligas/sortear', [TemporadaLigaController::class, 'sortear'])->name('temporadas.sortearLiga');
    Route::post('/temporadas/{temporada}/gestion-ligas/orden', [TemporadaLigaController::class, 'updateOrden'])->name('temporadas.updateOrdenLiga');
    
    // Ruta para estadísticas de liga en temporada
    Route::get('/temporadas/{temporada}/ligas/{liga}/estadisticas', [LigaEstadisticasController::class, 'show'])->name('temporadas.ligas.estadisticas');
    Route::get('/temporadas/{temporada}/ligas/{liga}/estadisticas/jugadores', [LigaEstadisticasController::class, 'jugadores'])->name('temporadas.ligas.estadisticas.jugadores');
    // Rutas para estadísticas de competiciones
    Route::get('/competiciones/{competicion}/estadisticas', [\App\Http\Controllers\CompeticionEstadisticasController::class, 'show'])->name('competiciones.estadisticas');
    Route::get('/competiciones/{competicion}/estadisticas/jugadores', [\App\Http\Controllers\CompeticionEstadisticasController::class, 'jugadores'])->name('competiciones.estadisticas.jugadores');
    Route::get('/competiciones/{competicion}/temporadas/{temporada}/estadisticas', [\App\Http\Controllers\CompeticionEstadisticasController::class, 'showPage'])->name('competiciones.temporadas.estadisticas');
    Route::get('/campeonatos/{campeonato}/eliminatorias', [\App\Http\Controllers\EliminatoriaController::class, 'show'])->name('campeonatos.eliminatorias');
    Route::post('/campeonatos/{campeonato}/eliminatorias/sortear', [\App\Http\Controllers\EliminatoriaController::class, 'sortear'])->name('campeonatos.eliminatorias.sortear');

    // Squad Battles
    Route::resource('squad-battles', SquadBattleController::class)->parameters(['squad-battles' => 'squadBattle']);
    Route::prefix('squad-battles/{squadBattle}')->group(function () {
        Route::post('divisiones', [DivisionController::class, 'store'])->name('squad-battles.divisiones.store');
        Route::put('divisiones/{division}', [DivisionController::class, 'update'])->name('squad-battles.divisiones.update');
        Route::delete('divisiones/{division}', [DivisionController::class, 'destroy'])->name('squad-battles.divisiones.destroy');
        Route::post('divisiones/{division}/equipos', [DivisionController::class, 'addEquipo'])->name('squad-battles.divisiones.addEquipo');
        Route::delete('divisiones/{division}/equipos/{divisionRival}', [DivisionController::class, 'removeEquipo'])->name('squad-battles.divisiones.removeEquipo');
        Route::patch('divisiones/{division}/orden', [DivisionController::class, 'updateOrden'])->name('squad-battles.divisiones.updateOrden');
        Route::get('divisiones/{division}/jugadores', [SquadBattleController::class, 'jugadores'])->name('squad-battles.divisiones.jugadores');
    });

    // Rivals Battles
    Route::resource('rivals-battles', RivalsBattleController::class)->parameters(['rivals-battles' => 'rivalsBattle']);
    Route::prefix('rivals-battles/{rivalsBattle}')->group(function () {
        Route::post('divisiones', [DivisionController::class, 'store'])->name('rivals-battles.divisiones.store');
        Route::put('divisiones/{division}', [DivisionController::class, 'update'])->name('rivals-battles.divisiones.update');
        Route::delete('divisiones/{division}', [DivisionController::class, 'destroy'])->name('rivals-battles.divisiones.destroy');
        Route::post('divisiones/{division}/equipos', [DivisionController::class, 'addEquipo'])->name('rivals-battles.divisiones.addEquipo');
        Route::delete('divisiones/{division}/equipos/{divisionRival}', [DivisionController::class, 'removeEquipo'])->name('rivals-battles.divisiones.removeEquipo');
        Route::patch('divisiones/{division}/orden', [DivisionController::class, 'updateOrden'])->name('rivals-battles.divisiones.updateOrden');
    });

    // Rivales con divisiones
    Route::prefix('rivales/{rival}')->group(function () {
        Route::post('divisiones', [DivisionController::class, 'store'])->name('rivales.divisiones.store');
        Route::put('divisiones/{division}', [DivisionController::class, 'update'])->name('rivales.divisiones.update');
        Route::delete('divisiones/{division}', [DivisionController::class, 'destroy'])->name('rivales.divisiones.destroy');
        Route::post('divisiones/{division}/equipos', [DivisionController::class, 'addEquipo'])->name('rivales.divisiones.addEquipo');
        Route::delete('divisiones/{division}/equipos/{divisionRival}', [DivisionController::class, 'removeEquipo'])->name('rivales.divisiones.removeEquipo');
        Route::patch('divisiones/{division}/orden', [DivisionController::class, 'updateOrden'])->name('rivales.divisiones.updateOrden');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
