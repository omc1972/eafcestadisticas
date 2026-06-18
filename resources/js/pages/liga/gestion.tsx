import { useState, useEffect } from 'react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { GripVertical, Save } from 'lucide-react';
import { toast } from 'sonner';

interface Equipo {
    id: number;
    nombre: string;
    codigo: string;
    escudo: string | null;
    estadio: { nombre: string } | null;
    entrenador: { nombre: string } | null;
}

interface Plantilla {
    id: number;
    orden_liga: number | null;
    equipo: Equipo;
}

interface Temporada {
    id: number;
    nombre: string;
}

interface Props {
    temporada: Temporada;
    plantillas: Plantilla[];
}

export default function GestionLiga({ temporada, plantillas }: Props) {
    const [equipos, setEquipos] = useState<Plantilla[]>([]);
    const [draggedItem, setDraggedItem] = useState<number | null>(null);
    const [hasChanges, setHasChanges] = useState(false);

    useEffect(() => {
        // Ordenar por orden_liga si existe, sino por id
        const ordenados = [...plantillas].sort((a, b) => {
            if (a.orden_liga !== null && b.orden_liga !== null) {
                return a.orden_liga - b.orden_liga;
            }
            return a.id - b.id;
        });
        setEquipos(ordenados);
    }, [plantillas]);

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Temporadas',
            href: '/temporadas',
        },
        {
            title: temporada.nombre,
            href: `/temporadas/${temporada.id}`,
        },
        {
            title: 'Gestión de Liga',
            href: `/temporadas/${temporada.id}/liga`,
        },
    ];

    const handleDragStart = (index: number) => {
        setDraggedItem(index);
    };

    const handleDragOver = (e: React.DragEvent<HTMLDivElement>, index: number) => {
        e.preventDefault();
        
        if (draggedItem === null || draggedItem === index) return;

        const newEquipos = [...equipos];
        const draggedEquipo = newEquipos[draggedItem];
        
        // Remover el elemento arrastrado
        newEquipos.splice(draggedItem, 1);
        // Insertar en la nueva posición
        newEquipos.splice(index, 0, draggedEquipo);
        
        setEquipos(newEquipos);
        setDraggedItem(index);
        setHasChanges(true);
    };

    const handleDragEnd = () => {
        setDraggedItem(null);
    };

    const handleSave = () => {
        const orden = equipos.map((plantilla, index) => ({
            plantilla_id: plantilla.id,
            orden: index + 1
        }));

        router.post(`/temporadas/${temporada.id}/liga/orden`, 
            { orden },
            {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Orden de liga guardado correctamente');
                    setHasChanges(false);
                },
                onError: () => {
                    toast.error('Error al guardar el orden de liga');
                }
            }
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Gestión de Liga - ${temporada.nombre}`} />
            
            <div className="container mx-auto p-6 max-w-4xl">
                <Card>
                    <CardHeader>
                        <CardTitle className="text-2xl">Gestión de Liga</CardTitle>
                        <CardDescription>
                            Organiza el orden de inicio de campaña para la temporada {temporada.nombre}.
                            Arrastra y suelta los equipos para cambiar su posición.
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        {equipos.length === 0 ? (
                            <div className="text-center py-8 text-muted-foreground">
                                No hay equipos en esta temporada
                            </div>
                        ) : (
                            <>
                                <div className="space-y-2">
                                    {equipos.map((plantilla, index) => (
                                        <div
                                            key={plantilla.id}
                                            draggable
                                            onDragStart={() => handleDragStart(index)}
                                            onDragOver={(e) => handleDragOver(e, index)}
                                            onDragEnd={handleDragEnd}
                                            className={`
                                                flex items-center gap-4 p-4 rounded-lg border bg-card
                                                hover:bg-accent cursor-move transition-colors
                                                ${draggedItem === index ? 'opacity-50' : ''}
                                            `}
                                        >
                                            <GripVertical className="h-5 w-5 text-muted-foreground flex-shrink-0" />
                                            
                                            <div className="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-primary-foreground font-bold flex-shrink-0">
                                                {index + 1}
                                            </div>

                                            {plantilla.equipo.escudo && (
                                                <img 
                                                    src={plantilla.equipo.escudo} 
                                                    alt={plantilla.equipo.nombre}
                                                    className="w-10 h-10 object-contain flex-shrink-0"
                                                />
                                            )}
                                            
                                            <div className="flex-1 min-w-0">
                                                <div className="font-semibold truncate">
                                                    {plantilla.equipo.nombre}
                                                </div>
                                                <div className="text-sm text-muted-foreground truncate">
                                                    {plantilla.equipo.estadio?.nombre || 'Sin estadio'} • {' '}
                                                    {plantilla.equipo.entrenador?.nombre || 'Sin entrenador'}
                                                </div>
                                            </div>

                                            <div className="text-xs text-muted-foreground font-mono flex-shrink-0">
                                                {plantilla.equipo.codigo}
                                            </div>
                                        </div>
                                    ))}
                                </div>

                                <div className="flex justify-end pt-4">
                                    <Button 
                                        onClick={handleSave}
                                        disabled={!hasChanges}
                                        className="gap-2"
                                    >
                                        <Save className="h-4 w-4" />
                                        Guardar Orden
                                    </Button>
                                </div>
                            </>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
