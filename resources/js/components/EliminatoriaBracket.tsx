import React from 'react';

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

interface RondaEliminatoria {
  nombre: string;
  orden: number;
  cruces: CruceEliminatoria[];
}

interface EliminatoriaBracketProps {
  rondas: RondaEliminatoria[];
}

export const EliminatoriaBracket: React.FC<EliminatoriaBracketProps> = ({ rondas }) => {
  return (
    <div className="flex gap-6 overflow-x-auto pb-4">
      {rondas.map((ronda) => (
        <div key={`${ronda.orden}-${ronda.nombre}`} className="min-w-[280px] shrink-0">
          <div className="mb-3 rounded-lg border border-gray-300 bg-gray-900 px-4 py-3 text-center text-sm font-semibold text-white">
            {ronda.nombre}
          </div>
          <div className="flex flex-col gap-4">
            {ronda.cruces.length === 0 ? (
              <div className="rounded-lg border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-500">
                Sin cruces
              </div>
            ) : (
              ronda.cruces.map((cruce) => (
                <div
                  key={cruce.id}
                  className="rounded-xl border border-gray-300 bg-gray-800 p-4 shadow"
                >
                  <div className="mb-3 text-center text-sm font-semibold text-white">
                    {cruce.equipo_local} vs {cruce.equipo_visitante}
                  </div>
                  <div className="flex flex-col gap-2">
                    {cruce.partidos.map((partido) => (
                      <div key={partido.id} className="rounded-md border border-gray-600 bg-gray-900 px-3 py-2 text-sm text-gray-100">
                        <div className="mb-1 text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                          {partido.etiqueta}
                        </div>
                        <div className="flex items-center justify-between gap-3">
                          <span className="truncate">{partido.equipo_local}</span>
                          <span className="font-semibold text-yellow-300">
                            {partido.goles_local !== null && partido.goles_visitante !== null
                              ? `${partido.goles_local} - ${partido.goles_visitante}`
                              : 'vs'}
                          </span>
                          <span className="truncate text-right">{partido.equipo_visitante}</span>
                        </div>
                        {partido.gestionar_url && (
                          <div className="mt-2 text-right">
                            <a
                              href={partido.gestionar_url}
                              className="text-xs font-semibold text-blue-300 hover:text-blue-200"
                            >
                              {partido.gestionado ? 'Editar partido' : 'Gestionar partido'}
                            </a>
                          </div>
                        )}
                      </div>
                    ))}
                  </div>
                  <div className="mt-3 border-t border-gray-600 pt-3 text-xs text-gray-300">
                    <div>
                      Global: {cruce.resultado_global ?? 'Pendiente'}
                    </div>
                    <div>
                      Ganador: {cruce.ganador ?? 'TBD'}
                    </div>
                  </div>
                </div>
              ))
            )}
          </div>
        </div>
      ))}
    </div>
  );
};
