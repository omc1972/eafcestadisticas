import React from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";

interface Props {
    liga: {
        id: number;
        nombre: string;
        pais: string | null;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: dashboard().url,
    },
];

const Show: React.FC<Props> = ({ liga }) => {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Liga: ${liga.nombre}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-4">{liga.nombre}</h1>
                <div className="space-y-4">
                    <div>
                        <label className="text-sm font-semibold text-muted-foreground">ID</label>
                        <p className="text-lg">{liga.id}</p>
                    </div>
                    <div>
                        <label className="text-sm font-semibold text-muted-foreground">Nombre</label>
                        <p className="text-lg">{liga.nombre}</p>
                    </div>
                    <div>
                        <label className="text-sm font-semibold text-muted-foreground">País</label>
                        <p className="text-lg">{liga.pais || "-"}</p>
                    </div>
                </div>
                <div className="flex gap-2 mt-4">
                    <Button
                        onClick={() => router.get(`/ligas/${liga.id}/edit`)}
                    >
                        Editar
                    </Button>
                    <Button
                        variant="secondary"
                        onClick={() => router.get("/ligas")}
                    >
                        Volver
                    </Button>
                </div>
            </div>
        </AppLayout>
    );
};

export default Show;
