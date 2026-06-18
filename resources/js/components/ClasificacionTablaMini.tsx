import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Trophy } from 'lucide-react';

interface Equipo {
    id: number;
    nombre: string;
    codigo: string;
    escudo: string | null;
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
}

interface Props {
    estadisticas: Estadistica[];
    mostrarCodigo?: boolean;
}

export default function ClasificacionTablaMini({ estadisticas, mostrarCodigo }: Props) {
    return (
        <div className="overflow-x-auto">
            <Table className="text-xs">
                <TableHeader>
                    <TableRow>
                        <TableHead className="w-8">#</TableHead>
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
                        <TableRow
                            key={stat.equipo.id}
                            className={`hover:bg-muted/50 ${index === 0 ? 'bg-green-500/10' : ''}`}
                        >
                            <TableCell className="font-bold">
                                {index === 0 && <Trophy className="h-3 w-3 inline text-yellow-500 mr-1" />}
                                {index + 1}
                            </TableCell>
                            <TableCell>
                                <div className="flex items-center gap-1">
                                    {stat.equipo.escudo && (
                                        <img
                                            src={`/storage/images/${stat.equipo.escudo}`}
                                            alt={stat.equipo.nombre}
                                            className="h-4 w-4 object-contain"
                                            onError={(e) => {
                                                e.currentTarget.src = '/storage/images/nologo.png';
                                            }}
                                        />
                                    )}
                                    <span className="font-medium">
                                        {mostrarCodigo ? stat.equipo.codigo : stat.equipo.nombre}
                                    </span>
                                </div>
                            </TableCell>
                            <TableCell className="text-center">{stat.partidos_jugados}</TableCell>
                            <TableCell className="text-center text-green-600">{stat.victorias}</TableCell>
                            <TableCell className="text-center text-yellow-600">{stat.empates}</TableCell>
                            <TableCell className="text-center text-red-600">{stat.derrotas}</TableCell>
                            <TableCell className="text-center">{stat.goles_favor}</TableCell>
                            <TableCell className="text-center">{stat.goles_contra}</TableCell>
                            <TableCell className={`text-center font-medium ${stat.diferencia_goles > 0 ? 'text-green-600' : stat.diferencia_goles < 0 ? 'text-red-600' : ''}`}>
                                {stat.diferencia_goles > 0 ? '+' : ''}
                                {stat.diferencia_goles}
                            </TableCell>
                            <TableCell className="text-center font-bold">{stat.puntos}</TableCell>
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </div>
    );
}
