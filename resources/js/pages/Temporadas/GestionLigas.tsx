import { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Save, Shuffle } from 'lucide-react';



interface Equipo {
    id: number;
    nombre: string;
    codigo: string;
    escudo: string | null;
    media: number | null;
}

interface PlantillaEquipo {
    plantilla_id: number | null;
    orden_liga: number | null;
    equipo: Equipo;
}

interface LigaData {
    liga: {
        id: number;
        nombre: string;
        pais: string | null;
    };
    campeonato_id: number | null;
    equipos: PlantillaEquipo[];
    jornadas: {
        numero: number;
        partidos: {
            local: Equipo;
            visitante: Equipo;
            creado: boolean;
            partido_id: number | null;
            resultado: string | null;
        }[];
    }[];
}

interface Temporada {
    id: number;
    nombre: string;
}

interface Props {
    temporada: Temporada;
    ligas: LigaData[];
    competicionLigaId: number | null;
}

export default function GestionLigas({ temporada, ligas: ligasIniciales, competicionLigaId }: Props) {
    const [ligas, setLigas] = useState<LigaData[]>(ligasIniciales);
    const [ligasModificadas, setLigasModificadas] = useState<Set<number>>(new Set());
    const [draggedItem, setDraggedItem] = useState<{ ligaId: number; index: number } | null>(null);
    const [jornadaActualPorLiga, setJornadaActualPorLiga] = useState<Record<number, number>>(() =>
        Object.fromEntries(ligasIniciales.map((liga) => [liga.liga.id, 0]))
    );

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Temporadas',
            href: '/temporadas',
        },
        {
            title: temporada.nombre,
            href: `/temporadas/${temporada.id}/edit`,
        },
        {
            title: 'Gestión de Ligas',
            href: `/temporadas/${temporada.id}/gestion-ligas`,
        },
    ];

    const handleSortear = (ligaId: number) => {
        router.post(`/temporadas/${temporada.id}/gestion-ligas/sortear`, 
            { liga_id: ligaId },
            {
                onSuccess: () => {
                    router.visit(`/temporadas/${temporada.id}/gestion-ligas`);
                },
                onError: (errors) => {
                    console.error('Error al sortear:', errors);
                }
            }
        );
    };

    const handleDragStart = (ligaId: number, index: number) => {
        setDraggedItem({ ligaId, index });
    };

    const handleDragOver = (e: React.DragEvent<HTMLDivElement>, ligaId: number, index: number) => {
        e.preventDefault();
        
        if (!draggedItem || draggedItem.ligaId !== ligaId || draggedItem.index === index) return;

        const newLigas = [...ligas];
        const ligaIndex = newLigas.findIndex(l => l.liga.id === ligaId);
        
        if (ligaIndex === -1) return;

        const newEquipos = [...newLigas[ligaIndex].equipos];
        const draggedEquipo = newEquipos[draggedItem.index];
        
        newEquipos.splice(draggedItem.index, 1);
        newEquipos.splice(index, 0, draggedEquipo);
        
        newLigas[ligaIndex] = { ...newLigas[ligaIndex], equipos: newEquipos };
        setLigas(newLigas);
        setDraggedItem({ ligaId, index });
        
        setLigasModificadas(prev => new Set(prev).add(ligaId));
    };

    const handleDragEnd = () => {
        setDraggedItem(null);
    };

    const handleSaveOrden = (ligaId: number) => {
        const liga = ligas.find(l => l.liga.id === ligaId);
        if (!liga) return;

        const orden = liga.equipos.map((eq, index) => ({
            equipo_id: eq.equipo.id,
            orden: index + 1
        }));

        router.post(`/temporadas/${temporada.id}/gestion-ligas/orden`, 
            { liga_id: ligaId, orden },
            {
                preserveScroll: true,
                onSuccess: () => {
                    setLigasModificadas(prev => {
                        const newSet = new Set(prev);
                        newSet.delete(ligaId);
                        return newSet;
                    });
                },
            }
        );
    };

    const DEFAULT_COMPETICION_ID = 4; // ID de FIFA para crear partidos desde gestión de ligas (parche rápido)

    const buildCrearPartidoUrl = (jornadaNumero: number, localId: number, visitanteId: number, campeonatoId: number | null, competicionId: number | null = DEFAULT_COMPETICION_ID) => {
        const params = new URLSearchParams({
            temporada_id: String(temporada.id),
            equipo_id: String(localId),
            visitante_id: String(visitanteId),
            visitante_es_rival: '0',
            local_es_rival: '0',
            partido_como_local: '1',
            jornada: String(jornadaNumero),
            auto_continuar: '1',
        });

        // Usar el competicionId pasado o el valor por defecto
        if (competicionId) {
            params.set('competicion_id', String(competicionId));
        }

        if (campeonatoId) {
            params.set('campeonato_id', String(campeonatoId));
        }

        return `/partidos/create?${params.toString()}`;
    };

    const getMediaColorClass = (media: number | null) => {
        if (media === null) {
            return 'border-slate-300 bg-slate-50 text-slate-500';
        }

        if (media >= 85) {
            return 'border-emerald-300 bg-emerald-50 text-emerald-700';
        }

        if (media >= 80) {
            return 'border-lime-300 bg-lime-50 text-lime-700';
        }

        if (media >= 75) {
            return 'border-amber-300 bg-amber-50 text-amber-700';
        }

        return 'border-rose-300 bg-rose-50 text-rose-700';
    };

    const changeJornada = (ligaId: number, nextIndex: number) => {
        setJornadaActualPorLiga((prev) => ({
            ...prev,
            [ligaId]: nextIndex,
        }));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Gestión de Ligas - ${temporada.nombre}`} />
            <div className="container mx-auto p-6 max-w-6xl">
                <Card>
                    <CardHeader>
                        <CardTitle className="text-2xl">Gestión de Ligas - {temporada.nombre}</CardTitle>
                        <CardDescription>
                            Sortea y organiza el orden de los equipos en cada liga. Usa el botón "Sortear" para orden aleatorio o arrastra para ordenar manualmente.
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-8">
                        {ligas.length === 0 ? (
                            <div className="text-center py-8 text-muted-foreground">
                                No hay equipos con ligas asignadas
                            </div>
                        ) : (
                            ligas.map((ligaData) => (
                                <div key={ligaData.liga.id} className="space-y-4">
                                    <div className="flex items-center justify-between">
                                        <div>
                                            <h3 className="text-xl font-bold">{ligaData.liga.nombre}</h3>
                                            {ligaData.liga.pais && (
                                                <p className="text-sm text-muted-foreground">{ligaData.liga.pais}</p>
                                            )}
                                        </div>
                                        <div className="flex gap-2">
                                            {ligasModificadas.has(ligaData.liga.id) && (
                                                <Button 
                                                    onClick={() => handleSaveOrden(ligaData.liga.id)}
                                                    className="gap-2"
                                                >
                                                    <Save className="h-4 w-4" />
                                                    Guardar Orden
                                                </Button>
                                            )}
                                            <Button 
                                                onClick={() => handleSortear(ligaData.liga.id)}
                                                variant="outline"
                                                className="gap-2"
                                            >
                                                <Shuffle className="h-4 w-4" />
                                                Sortear
                                            </Button>
                                        </div>
                                    </div>

                                    <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
                                        <Card>
                                            <CardHeader className="pb-3">
                                                <CardTitle className="text-base">Orden de equipos</CardTitle>
                                            </CardHeader>
                                            <CardContent className="pr-1">
                                                <div className="grid grid-cols-1 gap-2 md:grid-cols-2">
                                                    {ligaData.equipos.map((plantillaEquipo, index) => (
                                                        <div
                                                            key={plantillaEquipo.equipo.id}
                                                            draggable
                                                            onDragStart={() => handleDragStart(ligaData.liga.id, index)}
                                                            onDragOver={(e) => handleDragOver(e, ligaData.liga.id, index)}
                                                            onDragEnd={handleDragEnd}
                                                            className={`
                                                                flex items-center gap-2 rounded-lg border bg-card p-3
                                                                hover:bg-accent cursor-move transition-colors
                                                                ${draggedItem?.ligaId === ligaData.liga.id && draggedItem?.index === index ? 'opacity-50' : ''}
                                                            `}
                                                        >
                                                            {plantillaEquipo.equipo.escudo && (
                                                                <img
                                                                    src={`/storage/images/${plantillaEquipo.equipo.escudo}`}
                                                                    alt={plantillaEquipo.equipo.nombre}
                                                                    className="h-8 w-8 flex-shrink-0 object-contain"
                                                                />
                                                            )}

                                                            <div className="min-w-0 flex-1">
                                                                <div className="font-mono text-[11px] text-muted-foreground">
                                                                    {plantillaEquipo.equipo.codigo}
                                                                </div>
                                                                <div className="truncate text-sm font-semibold">
                                                                    {plantillaEquipo.equipo.nombre}
                                                                </div>
                                                            </div>

                                                            <div className={`flex-shrink-0 rounded-md border px-2 py-1 text-xs font-semibold ${getMediaColorClass(plantillaEquipo.equipo.media)}`}>
                                                                {plantillaEquipo.equipo.media !== null ? plantillaEquipo.equipo.media.toFixed(1) : '-'}
                                                            </div>
                                                        </div>
                                                    ))}
                                                </div>
                                            </CardContent>
                                        </Card>

                                        <Card>
                                            <CardHeader className="pb-3">
                                                <div className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                                    <CardTitle className="text-base">
                                                        {ligaData.jornadas.length > 0
                                                            ? `Jornada ${ligaData.jornadas[jornadaActualPorLiga[ligaData.liga.id] ?? 0].numero}`
                                                            : 'Jornadas'}
                                                    </CardTitle>
                                                    {ligaData.jornadas.length > 0 && (() => {
                                                        const jornadaIndex = jornadaActualPorLiga[ligaData.liga.id] ?? 0;

                                                        return (
                                                            <div className="flex items-center gap-1.5">
                                                                <Button
                                                                    variant="outline"
                                                                    size="sm"
                                                                    className="h-8 px-2 text-xs"
                                                                    disabled={jornadaIndex === 0}
                                                                    onClick={() => changeJornada(ligaData.liga.id, jornadaIndex - 1)}
                                                                >
                                                                    Jornada anterior
                                                                </Button>
                                                                <span className="min-w-16 text-center text-xs text-muted-foreground">
                                                                    {jornadaIndex + 1} / {ligaData.jornadas.length}
                                                                </span>
                                                                <Button
                                                                    variant="outline"
                                                                    size="sm"
                                                                    className="h-8 px-2 text-xs"
                                                                    disabled={jornadaIndex === ligaData.jornadas.length - 1}
                                                                    onClick={() => changeJornada(ligaData.liga.id, jornadaIndex + 1)}
                                                                >
                                                                    Siguiente jornada
                                                                </Button>
                                                            </div>
                                                        );
                                                    })()}
                                                </div>
                                            </CardHeader>
                                            <CardContent className="space-y-3 pr-1">
                                                {ligaData.jornadas.length === 0 ? (
                                                    <p className="text-sm text-muted-foreground">
                                                        Se necesitan al menos dos equipos para generar jornadas.
                                                    </p>
                                                ) : (
                                                    (() => {
                                                        const jornadaIndex = jornadaActualPorLiga[ligaData.liga.id] ?? 0;
                                                        const jornada = ligaData.jornadas[jornadaIndex];

                                                        return (
                                                            <div key={jornada.numero} className="space-y-2">
                                                                {jornada.partidos.map((partido, partidoIndex) => (
                                                                    <div
                                                                        key={`${jornada.numero}-${partido.local.id}-${partido.visitante.id}-${partidoIndex}`}
                                                                        className="flex items-center justify-between gap-3 rounded-md border p-3"
                                                                    >
                                                                        <div className="flex min-w-0 flex-1 items-center justify-between gap-2">
                                                                            <div className="flex min-w-0 flex-1 items-center gap-2">
                                                                                {partido.local.escudo ? (
                                                                                    <img
                                                                                        src={`/storage/images/${partido.local.escudo}`}
                                                                                        alt={partido.local.nombre}
                                                                                        className="h-7 w-7 object-contain"
                                                                                    />
                                                                                ) : (
                                                                                    <div className="h-7 w-7 rounded-full border" />
                                                                                )}
                                                                                <span className="truncate font-mono text-sm">{partido.local.codigo}</span>
                                                                            </div>

                                                                            <span className="px-2 text-sm font-semibold text-muted-foreground">
                                                                                {partido.resultado ?? '-'}
                                                                            </span>

                                                                            <div className="flex min-w-0 flex-1 items-center justify-end gap-2">
                                                                                <span className="truncate font-mono text-sm">{partido.visitante.codigo}</span>
                                                                                {partido.visitante.escudo ? (
                                                                                    <img
                                                                                        src={`/storage/images/${partido.visitante.escudo}`}
                                                                                        alt={partido.visitante.nombre}
                                                                                        className="h-7 w-7 object-contain"
                                                                                    />
                                                                                ) : (
                                                                                    <div className="h-7 w-7 rounded-full border" />
                                                                                )}
                                                                            </div>
                                                                        </div>

                                                                        <div className="flex shrink-0 items-center justify-end">
                                                                            {partido.partido_id ? (
                                                                                <Button asChild size="sm" variant="outline" className="h-8 px-2 text-xs">
                                                                                    <Link href={`/partidos/${partido.partido_id}`}>
                                                                                        Ver estadisticas
                                                                                    </Link>
                                                                                </Button>
                                                                            ) : (
                                                                                <Button asChild size="sm" variant="outline" className="h-8 px-2 text-xs">
                                                                                    <Link href={buildCrearPartidoUrl(jornada.numero, partido.local.id, partido.visitante.id, ligaData.campeonato_id, DEFAULT_COMPETICION_ID)}>
                                                                                        Jugar partido
                                                                                    </Link>
                                                                                </Button>
                                                                            )}
                                                                        </div>
                                                                    </div>
                                                                ))}
                                                            </div>
                                                        );
                                                    })()
                                                )}
                                            </CardContent>
                                        </Card>
                                    </div>

                                    <hr className="my-6" />
                                </div>
                            ))
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
