import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Trophy, TrendingUp, Users, Target, Award, Clock, Star } from 'lucide-react';

interface Equipo {
    id: number;
    nombre: string;
    codigo: string;
    escudo: string | null;
    valoracion?: number;
}

interface Estadistica {
    equipo: Equipo;
    orden_liga: number | null;
    partidos_jugados: number;
    victorias: number;
    empates: number;
    derrotas: number;
    goles_favor: number;
    goles_contra: number;
    diferencia_goles: number;
    puntos: number;
    tarjetas_amarillas: number;
    tarjetas_rojas: number;
    posesion_promedio: number;
    tiros_al_palo?: number;
    tiros_realizados?: number;
    tiros_recibidos?: number;
    tiros_sin_porteria?: number;
}

interface Jugador {
    id: number;
    nombre: string;
    posicion: string;
}

interface JugadorEstadistica {
    jugador: Jugador;
    equipo: string;
    goles: number;
    asistencias: number;
    tiros: number;
    tiros_a_puerta: number;
    tiros_al_palo: number;
    pases: number;
    pases_exitosos: number;
    regates_exitosos: number;
    posesion_ganada: number;
    posesion_perdida: number;
    distancia_recorrida: number;
    tarjetas_amarillas: number;
    tarjetas_rojas: number;
    partidos_jugados: number;
    minutos_jugados: number;
    rendimiento: number;
    rendimiento_promedio?: number;
    penalties_provocados: number;
    faltas_cometidas: number;
    faltas_recibidas: number;
    paradas: number;
    fueras_de_juego: number;
    jugador_del_partido: number;
    puntuacion_amonestaciones: number;
    penalties_parados: number;
}

interface Temporada {
    id: number;
    nombre: string;
}

interface Liga {
    id: number;
    nombre: string;
    pais: string | null;
}

interface Rival {
    id: number;
    nombre: string;
    codigo: string;
    valoracion: number;
}

interface PartidoLado {
    id: number | null;
    nombre: string;
    codigo: string;
}

interface Partido {
    id: number;
    jornada: number | string;
    equipo_id: number;
    visitante_id: number | null;
    rival_id: number;
    goles_equipo: number;
    goles_rival: number;
    partido_como_local: number;
    local_es_rival: boolean;
    visitante_es_rival: boolean;
    valor_equipo?: number;
    valor_rival?: number;
    equipo: Equipo;
    rival: Rival;
    local: PartidoLado;
    visitante: PartidoLado;
}

interface JugadoresData {
    topGoleadores: JugadorEstadistica[];
    topAsistentes: JugadorEstadistica[];
    topTirosAlPalo: JugadorEstadistica[];
    topPasadores: JugadorEstadistica[];
    topAmarillas: JugadorEstadistica[];
    topMinutos: JugadorEstadistica[];
    topRendimiento: JugadorEstadistica[];
    topKilometros: JugadorEstadistica[];
    topRecuperaciones: JugadorEstadistica[];
    topPerdidas: JugadorEstadistica[];
    topRegates: JugadorEstadistica[];
    topPenaltiesProvocados: JugadorEstadistica[];
    topFaltasCometidas: JugadorEstadistica[];
    topFaltasRecibidas: JugadorEstadistica[];
    topParadas: JugadorEstadistica[];
    topFuerasJuego: JugadorEstadistica[];
    topMVP: JugadorEstadistica[];
    topAmonestados: JugadorEstadistica[];
    topRojas: JugadorEstadistica[];
    topPenaltiesParados: JugadorEstadistica[];
}

interface Props {
    temporada: Temporada;
    liga: Liga;
    estadisticas: Estadistica[];
    partidos: Partido[];
    estadisticas_equipos: any;
}

export default function LigaEstadisticas({ temporada, liga, estadisticas, partidos, estadisticas_equipos }: Props) {
    const [jugadoresData, setJugadoresData] = useState<JugadoresData | null>(null);
    const [loadingJugadores, setLoadingJugadores] = useState(false);
    const [jornadaIndex, setJornadaIndex] = useState(0);

    // Función para determinar la dificultad según la diferencia (valor_equipo - valor_rival)
    // Escala en intervalos de 6, de -2 a +3 igualado, de +3 a +8, de +8 a +13, ... hasta un máximo de +25 y -25
    const getDifficultyBadge = (diferencia: number) => {
        if (diferencia <= -25) {
            return { text: '☠️ Imposible', color: 'bg-red-900', icon: '☠️' };
        } else if (diferencia <= -20) {
            return { text: '💀 Extremadamente difícil', color: 'bg-red-800', icon: '💀' };
        } else if (diferencia <= -15) {
            return { text: '😱 Muy difícil', color: 'bg-red-700', icon: '😱' };
        } else if (diferencia <= -8) {
            return { text: '😰 Difícil', color: 'bg-orange-600', icon: '😰' };
        } else if (diferencia <= -3) {
            return { text: '😟 Complicado', color: 'bg-orange-400', icon: '😟' };
        } else if (diferencia <= 3) {
            return { text: '😐 Igualado', color: 'bg-yellow-400', icon: '😐' };
        } else if (diferencia <= 8) {
            return { text: '🙂 Favorable', color: 'bg-green-400', icon: '🙂' };
        } else if (diferencia <= 13) {
            return { text: '😊 Muy favorable', color: 'bg-green-500', icon: '😊' };
        } else if (diferencia <= 18) {
            return { text: '😎 Muy superior', color: 'bg-emerald-500', icon: '😎' };
        } else if (diferencia <= 25) {
            return { text: '🏆 Paseo', color: 'bg-emerald-600', icon: '🏆' };
        } else {
            return { text: '🏆 Paseo', color: 'bg-emerald-700', icon: '🏆' };
        }
    };

    const handleTabChange = (value: string) => {
        if (value === 'jugadores' && !jugadoresData && !loadingJugadores) {
            setLoadingJugadores(true);
            fetch(`/temporadas/${temporada.id}/ligas/${liga.id}/estadisticas/jugadores`)
                .then((r) => r.json())
                .then((data) => { setJugadoresData(data); setLoadingJugadores(false); })
                .catch(() => setLoadingJugadores(false));
        }
    };

    const parseJornada = (value: number | string) => {
        if (typeof value === 'number') return value > 0 ? value : 1;
        const match = String(value).match(/(\d+)/);
        return match ? Number(match[1]) : 1;
    };

    // Agrupar partidos por jornada
    const partidosPorJornada = partidos.reduce((acc, p) => {
        const j = parseJornada(p.jornada);
        if (!acc[j]) acc[j] = [];
        acc[j].push(p);
        return acc;
    }, {} as Record<number, Partido[]>);
    const jornadas = Object.keys(partidosPorJornada).map(Number).sort((a, b) => a - b);
    const jornadaActual = jornadas[jornadaIndex] ?? null;

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Temporadas', href: '/temporadas' },
        { title: temporada.nombre, href: `/temporadas/${temporada.id}` },
        { title: liga.nombre, href: `/temporadas/${temporada.id}/ligas/${liga.id}/estadisticas` },
    ];

    // Helper: tabla top-5 equipos
    const TopEquiposTable = ({ data, field, label, colorClass }: { data: any[]; field: string; label: string; colorClass: string }) => (
        <Table>
            <TableHeader><TableRow><TableHead className="w-8">#</TableHead><TableHead>Equipo</TableHead><TableHead className="text-center">{label}</TableHead></TableRow></TableHeader>
            <TableBody>
                {data?.map((stat: any, idx: number) => (
                    <TableRow key={stat.equipo.id}>
                        <TableCell className="font-bold">{idx + 1}</TableCell>
                        <TableCell>
                            <div className="flex items-center gap-2">
                                {stat.equipo.escudo && <img src={`/storage/images/${stat.equipo.escudo}`} alt={stat.equipo.nombre} className="h-5 w-5 object-contain" onError={e => { (e.currentTarget as HTMLImageElement).src = '/storage/images/nologo.png'; }} />}
                                <span>{stat.equipo.nombre}</span>
                            </div>
                        </TableCell>
                        <TableCell className={`text-center font-bold ${colorClass}`}>{typeof stat[field] === 'number' ? stat[field] : (stat[field] ?? '-')}</TableCell>
                    </TableRow>
                ))}
            </TableBody>
        </Table>
    );

    // Helper: tabla top-10 jugadores
    const TopJugadoresTable = ({ data, field, label, colorClass, format }: { data: JugadorEstadistica[]; field: keyof JugadorEstadistica; label: string; colorClass: string; format?: (v: any) => string }) => (
        <Table>
            <TableHeader><TableRow><TableHead className="w-8">#</TableHead><TableHead>Jugador</TableHead><TableHead className="text-center">{label}</TableHead></TableRow></TableHeader>
            <TableBody>
                {data.map((jugador, index) => (
                    <TableRow key={jugador.jugador.id}>
                        <TableCell className="font-bold">{index + 1}</TableCell>
                        <TableCell>
                            <div className="text-sm">{jugador.jugador.nombre}</div>
                            <div className="text-xs text-muted-foreground">{jugador.equipo}</div>
                        </TableCell>
                        <TableCell className={`text-center font-bold ${colorClass}`}>
                            {format ? format(jugador[field]) : String(jugador[field] ?? 0)}
                        </TableCell>
                    </TableRow>
                ))}
            </TableBody>
        </Table>
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`${liga.nombre} - ${temporada.nombre}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex justify-between items-center mb-2">
                    <div>
                        <h1 className="text-2xl font-bold flex items-center gap-2">
                            <Trophy className="h-6 w-6 text-yellow-500" />
                            {liga.nombre}
                        </h1>
                        <p className="text-sm text-muted-foreground">
                            Temporada: {temporada.nombre}{liga.pais && ` • ${liga.pais}`}
                        </p>
                    </div>
                </div>

                {estadisticas.length > 0 ? (
                    <>
                        {/* Resumen */}
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                            <Card><CardHeader className="pb-2"><CardTitle className="text-xs font-medium text-muted-foreground">Equipos</CardTitle></CardHeader><CardContent><div className="flex items-center gap-2"><Users className="h-4 w-4 text-blue-500" /><span className="text-xl font-bold">{estadisticas.length}</span></div></CardContent></Card>
                            <Card><CardHeader className="pb-2"><CardTitle className="text-xs font-medium text-muted-foreground">Partidos</CardTitle></CardHeader><CardContent><div className="text-xl font-bold">{estadisticas.reduce((s, e) => s + e.partidos_jugados, 0)}</div></CardContent></Card>
                            <Card><CardHeader className="pb-2"><CardTitle className="text-xs font-medium text-muted-foreground flex items-center gap-1"><TrendingUp className="h-3 w-3 text-green-500" />Goles</CardTitle></CardHeader><CardContent><div className="text-xl font-bold text-green-600">{estadisticas.reduce((s, e) => s + e.goles_favor, 0)}</div></CardContent></Card>
                            <Card><CardHeader className="pb-2"><CardTitle className="text-xs font-medium text-muted-foreground">Goles/Partido</CardTitle></CardHeader><CardContent><div className="text-xl font-bold">{(estadisticas.reduce((s, e) => s + e.goles_favor, 0) / Math.max(estadisticas.reduce((s, e) => s + e.partidos_jugados, 0), 1)).toFixed(2)}</div></CardContent></Card>
                        </div>

                        {/* Tabs */}
                        <Tabs defaultValue="clasificacion" onValueChange={handleTabChange}>
                            <TabsList className="mb-4">
                                <TabsTrigger value="clasificacion">Clasificación</TabsTrigger>
                                <TabsTrigger value="equipos">Estadísticas Equipos</TabsTrigger>
                                <TabsTrigger value="jugadores">Estadísticas Jugadores</TabsTrigger>
                            </TabsList>

                            {/* TAB 1: Clasificación + Jornadas */}
                            <TabsContent value="clasificacion">
                                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    {/* Clasificación — 2/3 */}
                                    <div className="lg:col-span-2">
                                        <Card>
                                            <CardHeader><CardTitle>Tabla de Clasificación</CardTitle></CardHeader>
                                            <CardContent>
                                                <Table>
                                                    <TableHeader>
                                                        <TableRow>
                                                            <TableHead className="w-10">#</TableHead>
                                                            <TableHead>Equipo</TableHead>
                                                            <TableHead className="text-center">PJ</TableHead>
                                                            <TableHead className="text-center">V</TableHead>
                                                            <TableHead className="text-center">E</TableHead>
                                                            <TableHead className="text-center">D</TableHead>
                                                            <TableHead className="text-center">GF</TableHead>
                                                            <TableHead className="text-center">GC</TableHead>
                                                            <TableHead className="text-center">DG</TableHead>
                                                            <TableHead className="text-center font-bold">PTS</TableHead>
                                                        </TableRow>
                                                    </TableHeader>
                                                    <TableBody>
                                                        {estadisticas.map((stat, index) => (
                                                            <TableRow key={stat.equipo.id} className={index === 0 ? 'bg-green-500/10' : ''}>
                                                                <TableCell className="font-bold">
                                                                    {index === 0 && <Trophy className="h-4 w-4 inline text-yellow-500 mr-1" />}
                                                                    {index + 1}
                                                                </TableCell>
                                                                <TableCell>
                                                                    <div className="flex items-center gap-2">
                                                                        {stat.equipo.escudo && <img src={`/storage/images/${stat.equipo.escudo}`} alt={stat.equipo.nombre} className="h-6 w-6 object-contain" onError={e => { (e.currentTarget as HTMLImageElement).src = '/storage/images/nologo.png'; }} />}
                                                                        <span className="font-medium">{stat.equipo.nombre}</span>
                                                                    </div>
                                                                </TableCell>
                                                                <TableCell className="text-center">{stat.partidos_jugados}</TableCell>
                                                                <TableCell className="text-center text-green-600">{stat.victorias}</TableCell>
                                                                <TableCell className="text-center text-yellow-600">{stat.empates}</TableCell>
                                                                <TableCell className="text-center text-red-600">{stat.derrotas}</TableCell>
                                                                <TableCell className="text-center">{stat.goles_favor}</TableCell>
                                                                <TableCell className="text-center">{stat.goles_contra}</TableCell>
                                                                <TableCell className={`text-center font-medium ${stat.diferencia_goles > 0 ? 'text-green-600' : stat.diferencia_goles < 0 ? 'text-red-600' : ''}`}>
                                                                    {stat.diferencia_goles > 0 ? '+' : ''}{stat.diferencia_goles}
                                                                </TableCell>
                                                                <TableCell className="text-center font-bold text-lg">{stat.puntos}</TableCell>
                                                            </TableRow>
                                                        ))}
                                                    </TableBody>
                                                </Table>
                                            </CardContent>
                                        </Card>
                                    </div>

                                    {/* Jornadas — 1/3 */}
                                    <div className="lg:col-span-1">
                                        <Card className="h-full">
                                            <CardHeader>
                                                <div className="flex items-center justify-between gap-2">
                                                    <CardTitle>{jornadaActual ? `Jornada ${jornadaActual}` : 'Jornadas'}</CardTitle>
                                                    {jornadas.length > 0 && (
                                                        <div className="flex items-center gap-1">
                                                            <button
                                                                type="button"
                                                                className="rounded border px-2 py-1 text-xs disabled:opacity-50"
                                                                disabled={jornadaIndex === 0}
                                                                onClick={() => setJornadaIndex((v) => Math.max(0, v - 1))}
                                                            >
                                                                Anterior
                                                            </button>
                                                            <span className="text-xs text-muted-foreground">{jornadaIndex + 1}/{jornadas.length}</span>
                                                            <button
                                                                type="button"
                                                                className="rounded border px-2 py-1 text-xs disabled:opacity-50"
                                                                disabled={jornadaIndex >= jornadas.length - 1}
                                                                onClick={() => setJornadaIndex((v) => Math.min(jornadas.length - 1, v + 1))}
                                                            >
                                                                Siguiente
                                                            </button>
                                                        </div>
                                                    )}
                                                </div>
                                            </CardHeader>
                                            <CardContent className="pr-1">
                                                {jornadaActual ? (
                                                    <div className="space-y-1">
                                                        {partidosPorJornada[jornadaActual].map((partido) => {
                                                                const diff = (partido.valor_equipo ?? 0) - (partido.valor_rival ?? 0);
                                                                const badge = getDifficultyBadge(diff);
                                                            const gLocal = partido.goles_equipo;
                                                            const gVis = partido.goles_rival;
                                                            const isV = gLocal > gVis;
                                                            const isE = gLocal === gVis;
                                                                const rivalDebajoLocal = partido.local_es_rival;
                                                                const rivalDebajoVisitante = partido.visitante_es_rival;
                                                                return (
                                                                    <div key={partido.id} onClick={() => router.visit(`/partidos/${partido.id}`)} className="flex items-start gap-1.5 p-1.5 border rounded hover:bg-muted/50 transition-colors text-xs cursor-pointer">
                                                                        <div className="flex-1 min-w-0">
                                                                            <div className="grid grid-cols-[1fr_auto_1fr] items-start gap-2 min-w-0">
                                                                                <div className="min-w-0">
                                                                                    <div className="font-bold text-blue-700 truncate">{partido.local.codigo || '—'}</div>
                                                                                    {rivalDebajoLocal && (
                                                                                        <div className="text-[10px] text-muted-foreground truncate">{partido.rival?.nombre ?? '—'}</div>
                                                                                    )}
                                                                                </div>

                                                                                <div className="flex items-center gap-1 pt-[1px]">
                                                                                    <span className={`font-extrabold px-2 py-0.5 rounded whitespace-nowrap text-sm tracking-wide ${isV ? 'bg-green-500/20 text-green-700' : isE ? 'bg-yellow-500/20 text-yellow-700' : 'bg-red-500/20 text-red-700'}`}>
                                                                                        {gLocal}-{gVis}
                                                                                    </span>
                                                                                </div>

                                                                                <div className="min-w-0 text-right">
                                                                                    <div className="font-bold text-blue-700 truncate">{partido.visitante.codigo || '—'}</div>
                                                                                    {rivalDebajoVisitante && (
                                                                                        <div className="text-[10px] text-muted-foreground truncate">{partido.rival?.nombre ?? '—'}</div>
                                                                                    )}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <span className={`px-1 py-0.5 rounded text-[9px] font-medium text-white ${badge.color}`}>{badge.icon}</span>
                                                                    </div>
                                                                );
                                                            })}
                                                    </div>
                                                ) : (
                                                    <div className="text-sm text-muted-foreground">No hay jornadas disponibles</div>
                                                )}
                                            </CardContent>
                                        </Card>
                                    </div>
                                </div>
                            </TabsContent>

                            {/* TAB 2: Estadísticas de Equipos */}
                            <TabsContent value="equipos">
                                {estadisticas_equipos && (
                                    <div className="space-y-10">
                                        {/* ATAQUE */}
                                        <div>
                                            <h3 className="text-xl font-semibold mb-4 text-green-700">⚡ ATAQUE</h3>
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><Target className="h-5 w-5 text-green-600" />Top 5 Goles a Favor</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.goles_favor} field="goles_favor" label="GF" colorClass="text-green-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🎯</span>Top 5 Tiros a Puerta Realizados</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.tiros_a_puerta_realizados} field="tiros_a_puerta_realizados" label="TPR" colorClass="text-blue-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>💥</span>Top 5 Tiros Totales Realizados</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.tiros_realizados} field="tiros_realizados" label="TR" colorClass="text-orange-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🧊</span>Menos Tiros Totales</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.tiros_realizados_min} field="tiros_realizados" label="TR" colorClass="text-cyan-700" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🎯</span>Menos Tiros a Puerta</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.tiros_a_puerta_realizados_min} field="tiros_a_puerta_realizados" label="TPR" colorClass="text-sky-700" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🪵</span>Top 5 Tiros al Palo</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.tiros_al_palo} field="tiros_al_palo" label="Palo" colorClass="text-amber-700" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><Star className="h-5 w-5 text-blue-600" />Top 5 Posesión</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.posesion} field="posesion_promedio" label="%" colorClass="text-blue-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🟥</span>Top 5 Balones Perdidos</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.balones_perdidos_equipo} field="balones_perdidos_equipo" label="BP" colorClass="text-red-700" /></CardContent></Card>
                                            </div>
                                        </div>
                                        {/* DEFENSA */}
                                        <div>
                                            <h3 className="text-xl font-semibold mb-4 text-blue-700">🛡️ DEFENSA</h3>
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🟩</span>Top 5 Balones Ganados</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.balones_ganados_equipo} field="balones_ganados_equipo" label="BG" colorClass="text-green-700" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><Award className="h-5 w-5 text-red-600" />Menos Goles en Contra</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.goles_contra} field="goles_contra" label="GC" colorClass="text-red-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🧤</span>Top 5 Tiros a Puerta Recibidos</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.tiros_a_puerta_recibidos} field="tiros_a_puerta_recibidos" label="TPRec" colorClass="text-red-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🧤</span>Menos Tiros a Puerta Recibidos</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.tiros_a_puerta_recibidos_min} field="tiros_a_puerta_recibidos" label="TPRec" colorClass="text-green-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🎯</span>Top 5 Tiros Totales Recibidos</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.tiros_recibidos} field="tiros_recibidos" label="DTR" colorClass="text-red-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🛡️</span>Menos Tiros Recibidos</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.tiros_recibidos_min} field="tiros_recibidos" label="DTR" colorClass="text-green-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🛑</span>Top 5 Intercepciones</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.interceciones_equipo} field="interceciones_equipo" label="INT" colorClass="text-blue-700" /></CardContent></Card>
                                            </div>
                                        </div>
                                        {/* FÍSICO */}
                                        <div>
                                            <h3 className="text-xl font-semibold mb-4 text-purple-700">💪 FÍSICO</h3>
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><Clock className="h-5 w-5 text-green-600" />Top 5 Distancia Recorrida</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.distancia_recorrida} field="distancia_recorrida" label="Kms" colorClass="text-green-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>📊</span>Top 5 Pases</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.pases} field="pases" label="Pases" colorClass="text-purple-600" /></CardContent></Card>
                                            </div>
                                        </div>
                                        {/* TARJETAS */}
                                        <div>
                                            <h3 className="text-xl font-semibold mb-4 text-yellow-700">🟨 TARJETAS</h3>
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span className="text-yellow-500 text-xl">⚠</span>Top 5 Tarjetas Amarillas</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.tarjetas_amarillas} field="tarjetas_amarillas" label="TA" colorClass="text-yellow-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span className="text-red-600 text-xl">🟥</span>Top 5 Tarjetas Rojas</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.tarjetas_rojas} field="tarjetas_rojas" label="TR" colorClass="text-red-600" /></CardContent></Card>
                                            </div>
                                        </div>
                                        {/* PENALTIES */}
                                        <div>
                                            <h3 className="text-xl font-semibold mb-4 text-green-700">⚽ PENALTIES</h3>
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>⚽</span>Top 5 Penalties a Favor</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.penalties_a_favor} field="penalties_a_favor" label="Pen +" colorClass="text-green-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🚫</span>Top 5 Penalties en Contra</CardTitle></CardHeader><CardContent><TopEquiposTable data={estadisticas_equipos.penalties_en_contra} field="penalties_en_contra" label="Pen -" colorClass="text-red-600" /></CardContent></Card>
                                            </div>
                                        </div>
                                    </div>
                                )}
                            </TabsContent>

                            {/* TAB 3: Estadísticas de Jugadores */}
                            <TabsContent value="jugadores">
                                {loadingJugadores && (
                                    <div className="flex items-center justify-center py-16 text-muted-foreground">
                                        Cargando estadísticas de jugadores...
                                    </div>
                                )}
                                {!loadingJugadores && !jugadoresData && (
                                    <div className="flex items-center justify-center py-16 text-muted-foreground text-sm">
                                        Haz clic en esta pestaña para cargar las estadísticas individuales.
                                    </div>
                                )}
                                {jugadoresData && (
                                    <div className="space-y-8">
                                        {/* OFENSIVA */}
                                        <div>
                                            <h3 className="text-xl font-semibold mb-4 text-green-600">⚽ OFENSIVA</h3>
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><Target className="h-5 w-5 text-green-600" />Top 10 Goleadores</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topGoleadores} field="goles" label="G" colorClass="text-green-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><Award className="h-5 w-5 text-blue-600" />Top 10 Asistentes</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topAsistentes} field="asistencias" label="A" colorClass="text-blue-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🪵</span>Top 10 Tiros al Palo</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topTirosAlPalo} field="tiros_al_palo" label="Palo" colorClass="text-amber-700" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🤺</span>Top 10 Penalties Provocados</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topPenaltiesProvocados} field="penalties_provocados" label="PP" colorClass="text-blue-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🚩</span>Top 10 Fueras de Juego</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topFuerasJuego} field="fueras_de_juego" label="FJ" colorClass="text-orange-600" /></CardContent></Card>
                                            </div>
                                        </div>
                                        {/* PASES Y POSESIÓN */}
                                        <div>
                                            <h3 className="text-xl font-semibold mb-4 text-purple-600">🎯 PASES Y POSESIÓN</h3>
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><TrendingUp className="h-5 w-5 text-purple-600" />Top 10 Pasadores</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topPasadores} field="pases_exitosos" label="P" colorClass="text-purple-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>⚡</span>Top 10 Regates</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topRegates} field="regates_exitosos" label="R" colorClass="text-purple-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>⚽</span>Top 10 Pérdidas</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topPerdidas} field="posesion_perdida" label="P" colorClass="text-red-600" /></CardContent></Card>
                                            </div>
                                        </div>
                                        {/* DEFENSA */}
                                        <div>
                                            <h3 className="text-xl font-semibold mb-4 text-blue-600">🛡️ DEFENSA</h3>
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🛡️</span>Top 10 Recuperaciones</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topRecuperaciones} field="posesion_ganada" label="R" colorClass="text-green-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>❌</span>Top 10 Faltas Cometidas</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topFaltasCometidas} field="faltas_cometidas" label="FC" colorClass="text-red-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🤕</span>Top 10 Faltas Recibidas</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topFaltasRecibidas} field="faltas_recibidas" label="FR" colorClass="text-orange-600" /></CardContent></Card>
                                            </div>
                                        </div>
                                        {/* DISCIPLINA */}
                                        <div>
                                            <h3 className="text-xl font-semibold mb-4 text-yellow-600">🟨 DISCIPLINA</h3>
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span className="text-yellow-500 text-xl">⚠</span>Top 10 Amarillas</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topAmarillas} field="tarjetas_amarillas" label="TA" colorClass="text-yellow-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span className="text-red-600 text-xl">🟥</span>Top 10 Tarjetas Rojas</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topRojas} field="tarjetas_rojas" label="TR" colorClass="text-red-600" /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>📛</span>Top 10 Más Amonestados</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topAmonestados} field="puntuacion_amonestaciones" label="Pts" colorClass="text-red-600" /></CardContent></Card>
                                            </div>
                                        </div>
                                        {/* PORTEROS */}
                                        {(jugadoresData.topParadas.length > 0 || jugadoresData.topPenaltiesParados.length > 0) && (
                                            <div>
                                                <h3 className="text-xl font-semibold mb-4 text-teal-600">🧤 PORTEROS</h3>
                                                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                    {jugadoresData.topParadas.length > 0 && <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🧤</span>Top 10 Paradas</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topParadas} field="paradas" label="P" colorClass="text-green-600" /></CardContent></Card>}
                                                    {jugadoresData.topPenaltiesParados.length > 0 && <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🥅</span>Top 10 Penalties Parados</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topPenaltiesParados} field="penalties_parados" label="PP" colorClass="text-green-600" /></CardContent></Card>}
                                                </div>
                                            </div>
                                        )}
                                        {/* FÍSICO */}
                                        <div>
                                            <h3 className="text-xl font-semibold mb-4 text-orange-600">💪 FÍSICO</h3>
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🏃</span>Top 10 Kilómetros</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topKilometros} field="distancia_recorrida" label="Km" colorClass="text-blue-600" format={(v) => v ? `${Number(v).toFixed(2)} km` : '—'} /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><Clock className="h-5 w-5 text-orange-600" />Top 10 Minutos</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topMinutos} field="minutos_jugados" label="M" colorClass="text-orange-600" /></CardContent></Card>
                                            </div>
                                        </div>
                                        {/* RENDIMIENTO */}
                                        <div>
                                            <h3 className="text-xl font-semibold mb-4 text-yellow-600">🏆 RENDIMIENTO</h3>
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><Star className="h-5 w-5 text-yellow-500" />Top 10 Rendimiento</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topRendimiento} field="rendimiento_promedio" label="Prom" colorClass="text-yellow-600" format={(v) => v ? Number(v).toFixed(1) : '0'} /></CardContent></Card>
                                                <Card><CardHeader><CardTitle className="flex items-center gap-2"><span>🏆</span>Top 10 MVP</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topMVP} field="jugador_del_partido" label="MVP" colorClass="text-yellow-600" /></CardContent></Card>
                                            </div>
                                        </div>
                                    </div>
                                )}
                            </TabsContent>
                        </Tabs>
                    </>
                ) : (
                    <div className="text-center py-12">
                        <Trophy className="h-12 w-12 text-gray-400 mx-auto mb-4" />
                        <p className="text-gray-500 text-lg">No hay estadísticas disponibles</p>
                        <p className="text-gray-400 text-sm mt-2">Aún no hay partidos jugados en esta liga</p>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}

