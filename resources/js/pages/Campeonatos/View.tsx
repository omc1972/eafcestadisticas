import React from "react";
import { Head, Link } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";

interface Campeonato {
  id: number;
  nombre: string;
  tipo?: string;
  fecha_inicio?: string;
  fecha_fin?: string;
}

interface Props {
  campeonato: Campeonato;
}

const View: React.FC<Props> = ({ campeonato }) => {
  const breadcrumbs: BreadcrumbItem[] = [
    { title: "Dashboard", href: dashboard().url },
    { title: "Campeonatos", href: "/campeonatos" },
    { title: campeonato?.nombre ?? "Campeonato" },
  ];

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title={campeonato?.nombre ?? "Campeonato"} />
      <div className="max-w-3xl mx-auto p-6 bg-white rounded shadow">
        <h1 className="text-2xl font-bold mb-2">{campeonato?.nombre}</h1>
        {campeonato?.tipo && <div className="text-sm text-gray-600 mb-2">Tipo: {campeonato.tipo}</div>}
        <div className="text-sm text-gray-600 mb-4">
          {campeonato?.fecha_inicio && <span>Inicio: {campeonato.fecha_inicio}</span>}
          {campeonato?.fecha_fin && <span className="ml-4">Fin: {campeonato.fecha_fin}</span>}
        </div>
        <div className="flex gap-2">
          <Link href={`/campeonatos/${campeonato.id}/edit`} className="btn btn-secondary">Editar</Link>
          <Link href="/campeonatos" className="btn btn-light">Volver</Link>
        </div>
      </div>
    </AppLayout>
  );
};

export default View;
