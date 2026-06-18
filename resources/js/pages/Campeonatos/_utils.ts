import { Ziggy } from '@/ziggy';
import { route } from 'ziggy-js';

export function getRoute(name: string, params?: any) {
  return route(name, params, undefined, Ziggy);
}
