// Mapeo de estadísticas para porteros vs otros jugadores
// Reutilizamos los campos existentes de la BD pero con labels diferentes

export type StatGroup = 'salto' | 'parada' | 'saque' | 'reflejos' | 'velocidad' | 'posicion' | 'general';

export interface StatConfig {
  label: string;
  field: string;
  format?: 'number' | 'decimal';
  group: StatGroup;
}

export interface StatGroupConfig {
  title: string;
  stats: StatConfig[];
}

// Estadísticas para PORTEROS
export const GOALKEEPER_STATS: Record<StatGroup, StatGroupConfig> = {
  salto: {
    title: 'Salto',
    stats: [
      { label: 'Estirada', field: 'tiros_al_palo', format: 'number', group: 'salto' },
    ]
  },
  parada: {
    title: 'Parada',
    stats: [
      { label: 'Paradas', field: 'tiros_a_puerta', format: 'number', group: 'parada' },
    ]
  },
  saque: {
    title: 'Saque',
    stats: [
      { label: 'Saques', field: 'pases', format: 'number', group: 'saque' },
    ]
  },
  reflejos: {
    title: 'Reflejos',
    stats: [
      { label: 'Reflejos', field: 'regates_exitosos', format: 'number', group: 'reflejos' },
    ]
  },
  velocidad: {
    title: 'Velocidad',
    stats: [
      { label: 'Aceleración', field: 'posesion_ganada', format: 'number', group: 'velocidad' },
      { label: 'Velocidad', field: 'distancia_recorrida', format: 'decimal', group: 'velocidad' },
    ]
  },
  posicion: {
    title: 'Posición',
    stats: [
      { label: 'Colocación', field: 'pases_exitosos', format: 'number', group: 'posicion' },
    ]
  },
  general: {
    title: 'General',
    stats: [
      { label: 'Minutos', field: 'minutos', format: 'number', group: 'general' },
      { label: 'Partidos', field: 'partidos', format: 'number', group: 'general' },
      { label: 'Rendimiento', field: 'rendimiento', format: 'decimal', group: 'general' },
    ]
  }
};

// Estadísticas para JUGADORES (no porteros)
export const PLAYER_STATS: Record<StatGroup, StatGroupConfig> = {
  salto: {
    title: 'Disparos',
    stats: [
      { label: 'Tiros', field: 'tiros', format: 'number', group: 'salto' },
      { label: 'A Puerta', field: 'tiros_a_puerta', format: 'number', group: 'salto' },
      { label: 'Al Palo', field: 'tiros_al_palo', format: 'number', group: 'salto' },
    ]
  },
  parada: {
    title: 'Pases',
    stats: [
      { label: 'Pases', field: 'pases', format: 'number', group: 'parada' },
      { label: 'Exitosos', field: 'pases_exitosos', format: 'number', group: 'parada' },
    ]
  },
  saque: {
    title: 'Defensa',
    stats: [
      { label: 'Entradas', field: 'entradas', format: 'number', group: 'saque' },
      { label: 'Exitosas', field: 'entradas_exitosas', format: 'number', group: 'saque' },
    ]
  },
  reflejos: {
    title: 'Regates',
    stats: [
      { label: 'Regates', field: 'regates', format: 'number', group: 'reflejos' },
      { label: 'Exitosos', field: 'regates_exitosos', format: 'number', group: 'reflejos' },
    ]
  },
  velocidad: {
    title: 'Movilidad',
    stats: [
      { label: 'Posesión Ganada', field: 'posesion_ganada', format: 'number', group: 'velocidad' },
      { label: 'Posesión Perdida', field: 'posesion_perdida', format: 'number', group: 'velocidad' },
      { label: 'Distancia', field: 'distancia_recorrida', format: 'decimal', group: 'velocidad' },
    ]
  },
  posicion: {
    title: 'Disciplina',
    stats: [
      { label: 'Faltas Cometidas', field: 'faltas_cometidas', format: 'number', group: 'posicion' },
      { label: 'Faltas Recibidas', field: 'faltas_recibidas', format: 'number', group: 'posicion' },
      { label: 'Fuera de Juego', field: 'fueras_de_juego', format: 'number', group: 'posicion' },
    ]
  },
  general: {
    title: 'General',
    stats: [
      { label: 'Minutos', field: 'minutos', format: 'number', group: 'general' },
      { label: 'Partidos', field: 'partidos', format: 'number', group: 'general' },
      { label: 'Rendimiento', field: 'rendimiento', format: 'decimal', group: 'general' },
      { label: 'MVP', field: 'jugador_del_partido', format: 'number', group: 'general' },
    ]
  }
};

/**
 * Obtiene la configuración de estadísticas según la posición del jugador
 */
export function getStatsConfig(posicion: string | null) {
  const isGoalkeeper = posicion === 'POR';
  return isGoalkeeper ? GOALKEEPER_STATS : PLAYER_STATS;
}

/**
 * Formatea un valor de estadística según su tipo
 */
export function formatStatValue(value: any, format?: 'number' | 'decimal'): string {
  if (value === null || value === undefined) return '-';
  
  if (format === 'decimal') {
    return typeof value === 'number' ? value.toFixed(2) : '-';
  }
  
  return String(value || '-');
}

/**
 * Obtiene el orden de grupos para mostrar
 */
export function getGroupOrder(): StatGroup[] {
  return ['salto', 'parada', 'saque', 'reflejos', 'velocidad', 'posicion', 'general'];
}
