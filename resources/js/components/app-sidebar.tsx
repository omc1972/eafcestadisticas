import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { edit as appearanceEdit } from '@/routes/appearance';
import squadBattles from '@/routes/squad-battles';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { LayoutGrid, LayoutList, Users, Trophy, Shirt, Shield, Building, Goal, Swords, UserRound, Settings } from 'lucide-react';
import AppLogo from './app-logo';

import { route } from 'ziggy-js';
import { Ziggy } from '@/ziggy';

const r = (name: string, params: Record<string, any> = {}) => {
    const url = route(name, params, undefined, Ziggy);
    // Devolver siempre ruta relativa para no forzar dominio/puerto
    if (url.startsWith('http')) {
        return new URL(url).pathname;
    }
    return url.startsWith('/') ? url : '/' + url;
};

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Entrenadores',
        href: r('entrenadores.index'),
        icon: UserRound,
    },
    {
        title: 'Jugadores',
        href: r('jugadores.index'),
        icon: UserRound,
    },
    {
        title: 'Equipaciones',
        href: r('equipaciones.index'),
        icon: Shirt,
    },
    {
        title: 'Estadios',
        href: r('estadios.index'),
        icon: Building,
    },    
    {
        title: 'Equipos',
        href: r('equipos.index'),
        icon: Shield,
    },
    {
        title: 'Rivales',
        href: r('rivales.index'),
        icon: Shield,
    },
    {
        title: 'Plantillas',
        href: r('plantillas.index'),
        icon: Shield,
    },
    {
        title: 'Competiciones',
        href: r('competiciones.index'),
        icon: Trophy,
    },
    {
        title: 'Ligas',
        href: r('ligas.index'),
        icon: Trophy,
    },
    {
        title: 'Temporadas',
        href: r('temporadas.index'),
        icon: Goal,
    },
    {
        title: 'Partidos',
        href: r('partidos.index'),
        icon: Swords,
    },
    {
        title: 'Campeonatos',
        href: r('campeonatos.index'),
        icon: Trophy,
    },
    {
        title: 'Squad Battles',
        href: squadBattles.index(),
        icon: Trophy,
    },
    {
        title: 'Rivals',
        href: r('rivals-battles.index'),
        icon: Trophy,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Apariencia',
        href: appearanceEdit(),
        icon: Settings,
    },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
