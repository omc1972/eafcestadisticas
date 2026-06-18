import React, { useState } from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Card, CardContent } from "@/components/ui/card";
import AccionesTabla from "@/components/acciones-tabla";
import ControlesPaginacion from "@/components/controles-paginacion";

type Plantilla = {
    id: number;
    equipo: { id: number; nombre: string; escudo: string | null };
    temporada: { nombre: string };
    liga: { nombre: string } | null;
    campeonato: { nombre: string } | null;
    num_jugadores: number;
    media_promedio: number | null;
};

interface Props {
    plantillas: Plantilla[];
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: "Dashboard", href: dashboard().url },
];

const Index: React.FC<Props> = ({ plantillas }) => {
    const [globalFilter, setGlobalFilter] = useState("");
    const [currentPage, setCurrentPage] = useState(1);
    const rowsPerPage = 20;

    const filtered = plantillas.filter(
        (p) =>
            p.equipo.nombre.toLowerCase().includes(globalFilter.toLowerCase()) ||
            p.temporada.nombre.toLowerCase().includes(globalFilter.toLowerCase()) ||
            (p.liga?.nombre ?? "").toLowerCase().includes(globalFilter.toLowerCase())
    );

    const paginated = filtered.slice(
        (currentPage - 1) * rowsPerPage,
        currentPage * rowsPerPage
    );

    const totalPages = Math.ceil(filtered.length / rowsPerPage);

    const getMediaColor = (media: number | null) => {
        if (media === null) return "text-gray-400";
        if (media >= 85) return "text-yellow-400";
        if (media >= 75) return "text-green-500";
        if (media >= 65) return "text-blue-400";
        return "text-gray-400";
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Plantillas" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-2">Plantillas</h1>
                <div className="flex justify-between items-center mb-4">
                    <Input
                        placeholder="Buscar por equipo, temporada o liga..."
                        value={globalFilter}
                        onChange={(e) => setGlobalFilter(e.target.value)}
                        className="max-w-md"
                    />
                    <Button
                        onClick={() => router.get("/plantillas/create")}
                        className="bg-blue-600 text-white hover:bg-blue-700"
                    >
                        +
                    </Button>
                </div>

                {paginated.length > 0 ? (
                    <>
                        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            {paginated.map((plantilla) => (
                                <Card
                                    key={plantilla.id}
                                    className="cursor-pointer hover:ring-2 hover:ring-blue-500 transition-all relative group"
                                    onClick={() => router.get(`/plantillas/${plantilla.id}/edit`)}
                                >
                                    <CardContent className="p-3 flex flex-col items-center gap-2">
                                        {/* Escudo */}
                                        <div className="h-14 w-14 flex items-center justify-center">
                                            <img
                                                src={
                                                    plantilla.equipo.escudo
                                                        ? `/storage/images/${plantilla.equipo.escudo}`
                                                        : "/storage/images/nologo.png"
                                                }
                                                alt={plantilla.equipo.nombre}
                                                className="h-12 w-12 object-contain"
                                                onError={(e) => {
                                                    e.currentTarget.src = "/storage/images/nologo.png";
                                                }}
                                            />
                                        </div>

                                        {/* Nombre equipo */}
                                        <p className="font-bold text-sm text-center leading-tight line-clamp-2">
                                            {plantilla.equipo.nombre}
                                        </p>

                                        {/* Temporada */}
                                        <p className="text-xs text-muted-foreground text-center">
                                            {plantilla.temporada.nombre}
                                        </p>

                                        {/* Liga */}
                                        {plantilla.liga && (
                                            <p className="text-xs text-blue-400 text-center">
                                                {plantilla.liga.nombre}
                                            </p>
                                        )}

                                        {/* Media */}
                                        <div className="mt-1">
                                            <span
                                                className={`text-lg font-bold ${getMediaColor(plantilla.media_promedio)}`}
                                            >
                                                {plantilla.media_promedio !== null
                                                    ? plantilla.media_promedio
                                                    : "-"}
                                            </span>
                                            <span className="text-xs text-muted-foreground ml-1">media</span>
                                        </div>

                                        {/* Jugadores */}
                                        <p className="text-xs text-muted-foreground">
                                            {plantilla.num_jugadores} jugadores
                                        </p>

                                        {/* Acciones */}
                                        <div
                                            className="w-full mt-1"
                                            onClick={(e) => e.stopPropagation()}
                                        >
                                            <AccionesTabla
                                                id={plantilla.id}
                                                basePath="/plantillas"
                                            />
                                        </div>
                                    </CardContent>
                                </Card>
                            ))}
                        </div>

                        {totalPages > 1 && (
                            <ControlesPaginacion
                                currentPage={currentPage}
                                totalPages={totalPages}
                                onPageChange={(page) => setCurrentPage(page)}
                            />
                        )}
                    </>
                ) : (
                    <div className="text-center py-8">
                        <p className="text-gray-500 text-lg">No se encontraron plantillas</p>
                        <p className="text-gray-400 text-sm">
                            No hay plantillas registradas en el sistema
                        </p>
                    </div>
                )}
            </div>
        </AppLayout>
    );
};

export default Index;
