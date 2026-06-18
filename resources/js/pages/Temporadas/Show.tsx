import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { TrendingUp, TrendingDown, Trophy, Target, Users, ChevronDown, ChevronUp } from 'lucide-react';
import ClasificacionTablaMini from '@/components/ClasificacionTablaMini';

interface Liga {
    id: number;
    nombre: string;
    pais: string | null;
    equipos_count: number;
    partidos_jugados: number;
    goles_favor: number;
    goles_contra: number;
    diferencia_goles: number;
    victorias: number;
    empates: number;
    derrotas: number;
}

interface Temporada {
    id: number;
    nombre: string;
    activo: boolean;
}

interface Props {
    temporada: Temporada;
    ligas: Liga[];
}


export default function Show({ temporada, ligas }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Temporadas',
            href: '/temporadas',
        },
        {
            title: temporada.nombre,
            href: `/temporadas/${temporada.id}`,
        },
    ];

    // Todas las ligas desplegadas por defecto
    const initialExpanded: { [ligaId: number]: boolean } = {};
    ligas.forEach(liga => { initialExpanded[liga.id] = true; });
    const [expanded, setExpanded] = useState<{ [ligaId: number]: boolean }>(initialExpanded);
    const [clasificaciones, setClasificaciones] = useState<{ [ligaId: number]: any[] }>({});
    const [loading, setLoading] = useState<{ [ligaId: number]: boolean }>({});

    // Cargar todas las clasificaciones al montar
    React.useEffect(() => {
        ligas.forEach(async (liga) => {
            if (!clasificaciones[liga.id] && !loading[liga.id]) {
                setLoading((prev) => ({ ...prev, [liga.id]: true }));
                try {
                    const res = await fetch(`/temporadas/${temporada.id}/ligas/${liga.id}/estadisticas?onlyClasificacion=1`);
                    if (res.ok) {
                        const data = await res.json();
                        setClasificaciones((prev) => ({ ...prev, [liga.id]: data.estadisticas || [] }));
                    }
                } finally {
                    setLoading((prev) => ({ ...prev, [liga.id]: false }));
                }
            }
        });
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    const handleExpand = (ligaId: number) => {
        setExpanded((prev) => ({ ...prev, [ligaId]: !prev[ligaId] }));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Temporada - ${temporada.nombre}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex justify-between items-center mb-4">
                    <div>
                        <h1 className="text-2xl font-bold">{temporada.nombre}</h1>
                        <p className="text-sm text-muted-foreground">
                            {temporada.activo ? 'Temporada Activa' : 'Temporada Inactiva'}
                        </p>
                    </div>
                    <Button
                        variant="outline"
                        onClick={() => router.get(`/temporadas/${temporada.id}/edit`)}
                    >
                        Editar Temporada
                    </Button>
                </div>

                {ligas.length > 0 ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {ligas.map((liga) => (
                            <Card key={liga.id} className="transition-shadow">
                                <CardHeader
                                    className="cursor-pointer hover:shadow-lg flex flex-row items-center justify-between"
                                    onClick={() => handleExpand(liga.id)}
                                >
                                    <div className="flex items-center gap-2">
                                        <Trophy className="h-5 w-5 text-yellow-500" />
                                        {liga.nombre}
                                    </div>
                                    <Button variant="ghost" size="icon" tabIndex={-1} type="button">
                                        {expanded[liga.id] ? <ChevronUp className="w-5 h-5" /> : <ChevronDown className="w-5 h-5" />}
                                    </Button>
                                </CardHeader>
                                <CardContent>
                                    <div className="space-y-3">
                                        <div className="flex items-center justify-between text-sm">
                                            <span className="flex items-center gap-2 text-muted-foreground">
                                                <Users className="h-4 w-4" />
                                                Equipos
                                            </span>
                                            <span className="font-semibold">{liga.equipos_count}</span>
                                        </div>
                                        <div className="flex items-center justify-between text-sm">
                                            <span className="flex items-center gap-2 text-muted-foreground">
                                                <Target className="h-4 w-4" />
                                                Partidos
                                            </span>
                                            <span className="font-semibold">{liga.partidos_jugados}</span>
                                        </div>
                                        <div className="border-t pt-3">
                                            <div className="grid grid-cols-3 gap-2 text-center text-sm">
                                                <div>
                                                    <p className="text-green-600 font-bold">{liga.victorias}</p>
                                                    <p className="text-xs text-muted-foreground">V</p>
                                                </div>
                                                <div>
                                                    <p className="text-yellow-600 font-bold">{liga.empates}</p>
                                                    <p className="text-xs text-muted-foreground">E</p>
                                                </div>
                                                <div>
                                                    <p className="text-red-600 font-bold">{liga.derrotas}</p>
                                                    <p className="text-xs text-muted-foreground">D</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="border-t pt-3">
                                            <div className="flex items-center justify-between text-sm">
                                                <span className="flex items-center gap-2 text-muted-foreground">
                                                    <TrendingUp className="h-4 w-4 text-green-500" />
                                                    Goles a Favor
                                                </span>
                                                <span className="font-semibold text-green-600">{liga.goles_favor}</span>
                                            </div>
                                            <div className="flex items-center justify-between text-sm mt-2">
                                                <span className="flex items-center gap-2 text-muted-foreground">
                                                    <TrendingDown className="h-4 w-4 text-red-500" />
                                                    Goles en Contra
                                                </span>
                                                <span className="font-semibold text-red-600">{liga.goles_contra}</span>
                                            </div>
                                            <div className="flex items-center justify-between text-sm mt-2 pt-2 border-t">
                                                <span className="font-medium">Diferencia</span>
                                                <span
                                                    className={`font-bold ${
                                                        liga.diferencia_goles > 0
                                                            ? 'text-green-600'
                                                            : liga.diferencia_goles < 0
                                                            ? 'text-red-600'
                                                            : 'text-gray-500'
                                                    }`}
                                                >
                                                    {liga.diferencia_goles > 0 ? '+' : ''}
                                                    {liga.diferencia_goles}
                                                </span>
                                            </div>
                                        </div>
                                        {/* BOTÓN ESTADÍSTICAS LIGA */}
                                        <div className="flex justify-end mt-2">
                                            <a
                                                href={`/temporadas/${temporada.id}/ligas/${liga.id}/estadisticas`}
                                                className="inline-flex items-center px-3 py-1.5 rounded bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700 transition-colors"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                Ver estadísticas de la liga
                                            </a>
                                        </div>
                                    </div>
                                    {/* Tabla de clasificación mini */}
                                    {expanded[liga.id] && (
                                        <div className="mt-4">
                                            {loading[liga.id] ? (
                                                <div className="text-center text-xs text-muted-foreground py-2">Cargando clasificación...</div>
                                            ) : clasificaciones[liga.id] ? (
                                                <ClasificacionTablaMini estadisticas={clasificaciones[liga.id]} mostrarCodigo />
                                            ) : null}
                                        </div>
                                    )}
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                ) : (
                    <div className="text-center py-12">
                        <Trophy className="h-12 w-12 text-gray-400 mx-auto mb-4" />
                        <p className="text-gray-500 text-lg">No hay ligas en esta temporada</p>
                        <p className="text-gray-400 text-sm mt-2">
                            Añade equipos a las ligas y ordénalos desde "Sortear Ligas"
                        </p>
                        <Button
                            variant="outline"
                            className="mt-4"
                            onClick={() => router.get(`/temporadas/${temporada.id}/gestion-ligas`)}
                        >
                            Ir a Gestión de Ligas
                        </Button>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
