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
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
