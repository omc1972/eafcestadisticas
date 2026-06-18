import React from 'react';
import { EliminatoriaBracket } from '@/components/EliminatoriaBracket';
import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

interface PartidoCruce {
  id: number;
  etiqueta: string;
  equipo_local: string;
  equipo_visitante: string;
  goles_local: number | null;
  goles_visitante: number | null;
  gestionado?: boolean;
  gestionar_url?: string | null;
}

interface CruceEliminatoria {
  id: number;
  orden: number;
  equipo_local: string;
  equipo_visitante: string;
  resultado_global: string | null;
  ganador: string | null;
  partidos: PartidoCruce[];
}

interface EquipoParticipante {
  id: number;
  nombre: string;
  escudo: string | null;
}

interface RondaEliminatoria {
  nombre: string;
  orden: number;
  cruces: CruceEliminatoria[];
}

interface Props {
  campeonato: any;
  equipos_participantes: EquipoParticipante[];
  eliminatoria_rondas: RondaEliminatoria[];
}

export default function BracketEliminatoria({ campeonato, equipos_participantes, eliminatoria_rondas }: Props) {
  const handleSortearCopa = () => {
    router.post(`/campeonatos/${campeonato.id}/eliminatorias/sortear`, {}, {
      onSuccess: () => router.visit(`/campeonatos/${campeonato.id}/eliminatorias`),
      onError: (errors) => console.error('Error al sortear copa:', errors),
    });
  };

  return (
    <AppLayout>
      <Head title={`Eliminatorias - ${campeonato.nombre}`} />
      <div className="container mx-auto p-6 max-w-6xl">
        <h1 className="text-2xl font-bold mb-6">Árbol de Eliminatorias - {campeonato.nombre}</h1>
        <div className="mb-8 flex items-center justify-between">
          <div>
            <h2 className="text-lg font-semibold mb-2">Equipos participantes</h2>
               <div className="flex flex-wrap gap-4">
                 {equipos_participantes.map(eq => (
                   <div key={eq.id} className="flex items-center gap-2 border border-gray-400 rounded px-3 py-1 bg-gray-800 text-white">
                     {eq.escudo && <img src={`/storage/images/${eq.escudo}`} alt={eq.nombre} className="w-6 h-6 object-contain" />}
                     <span>{eq.nombre}</span>
                   </div>
                 ))}
               </div>
          </div>
             <Button onClick={handleSortearCopa} className="h-10 px-6 text-base font-semibold bg-gray-800 text-white border border-gray-400 hover:bg-gray-700">
               Sortear Copa
             </Button>
        </div>
        <EliminatoriaBracket rondas={eliminatoria_rondas} />
      </div>
    </AppLayout>
  );
}
