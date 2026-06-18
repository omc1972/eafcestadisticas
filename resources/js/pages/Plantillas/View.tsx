import React, { useState } from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import {
  Table,
  TableHeader,
  TableRow,
  TableHead,
  TableCell,
  TableBody,
} from "@/components/ui/table";
import ControlesPaginacion from "@/components/controles-paginacion";

interface Temnporada {
  id: number;
  nombre: string;

}

interface Jugador {
  id: number;
  nombre: string;
  dorsal: number;
  posicion: string;
  nacionalidad: string;
  resumen:[];
}

interface Equipo {
  id: number;
  nombre: string;
}

interface Plantilla {
    id: number;
    temporada: Temnporada;
    equipo: Equipo;
  campeonato?: { nombre: string };
    jugadores: Jugador[];
}

interface Props {
  plantilla: Plantilla;
}

const Index: React.FC<Props> = ({ plantilla }) => {
  return (
    <AppLayout breadcrumbs={[{ title: "Plantillas", href: "/plantillas" }]}>
      <Head title={`Plantilla`} />
        <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
             <h2 className="text-3xl font-bold flex items-center gap-2">
              <img 
                src={`/storage/images/${plantilla.equipo.escudo}`} 
                alt="escudo" 
                className="w-6 h-6 object-contain"
              />
              {plantilla.temporada.nombre}  ({plantilla.media.toFixed(1)})
            </h2>
            <p className="text-muted-foreground">
              Campeonato: {plantilla.campeonato?.nombre ?? "-"}
            </p>
             {/* Tabla jugadores 3/4 */}
                    <div className="md:w-4/4">
                      <Card>
                        <CardHeader>
                          <CardTitle>Plantilla</CardTitle>
                        </CardHeader>
                        <CardContent>
                          <Table>
                            <TableHeader>
                              <TableRow>
                                <TableHead>Dorsal</TableHead>
                                <TableHead>Posicion</TableHead>
                                <TableHead>Jugador</TableHead>
                                <TableHead>Media</TableHead>
                                <TableHead>Pais</TableHead>
                                <TableHead>Goles</TableHead>
                                <TableHead>Asistencias</TableHead>
                                <TableHead>Minutos</TableHead>
                                <TableHead>Km</TableHead>
                                <TableHead>Rendimiento</TableHead>
                              </TableRow>
                            </TableHeader>
                            <TableBody>
                              {plantilla.jugadores.map((j) => {
       
                                return (
                                  <TableRow key={j.id} className= "">
                                    <TableCell>{j.pivot.dorsal}</TableCell>
                                    <TableCell>{j.posicion}</TableCell>
                                    <TableCell>{j.nombre}</TableCell>
                                    <TableCell>{j.media}</TableCell>
                                    <TableCell>{j.nacionalidad}</TableCell>       
                                    <TableCell>{j.resumen['goles']}</TableCell> 
                                    <TableCell>{j.resumen['asistencias']}</TableCell>
                                    <TableCell>{j.resumen['minutos']}</TableCell> 
                                    <TableCell>{(j.resumen['distancia_recorrida']/(j.resumen['partidos']>0?j.resumen['partidos']:1)).toFixed(1)}</TableCell>      
                                    <TableCell>{(j.resumen['rendimiento']/(j.resumen['partidos']>0?j.resumen['partidos']:1)).toFixed(1)}</TableCell>                                
                                  </TableRow>
                                );
                              })}
                            </TableBody>
                          </Table>
            

                          
                        </CardContent>
                      </Card>
                    </div>
        </div>
    </AppLayout>
  );
};

export default Index;