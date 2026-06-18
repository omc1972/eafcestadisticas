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
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { BookOpen, Folder, LayoutGrid, LayoutList, Users } from 'lucide-react';
import AppLogo from './app-logo';

import { route } from 'ziggy-js';
import { Ziggy } from '@/ziggy';

const r = (name: string, params: Record<string, any> = {}) => {
    const url = route(name, params, undefined, Ziggy);
    
    // Si la URL no comienza con http, agregar el dominio completo
    if (!url.startsWith('http')) {
        return `${Ziggy.url}${url.startsWith('/') ? url : '/' + url}`;
    }
    
    return url;
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
        icon: Users,
    },
    {
        title: 'Equipaciones',
        href: r('equipaciones.index'),
        icon: Users,
    },
    {
        title: 'Estadios',
        href: r('estadios.index'),
        icon: Users,
    },
    {
        title: 'Jugadores',
        href: r('jugadores.index'),
        icon: Users,
    },
    {
        title: 'Equipos',
        href: r('equipos.index'),
        icon: Users,
    },
    {
        title: 'Ligas',
        href: r('ligas.index'),
        icon: Users,
    },
    {
        title: 'Rivales',
        href: r('rivales.index'),
        icon: Users,
    },
    {
        title: 'Temporadas',
        href: r('temporadas.index'),
        icon: Users,
    },
    {
        title: 'Plantillas',
        href: r('plantillas.index'),
        icon: Users,
    },
    {
        title: 'Partidos',
        href: r('partidos.index'),
        icon: Users,
    },
    {
        title: 'Campeonatos',
        href: r('campeonatos.index'),
        icon: Users,
    },
    {
        title: 'Competiciones',
        href: `${Ziggy.url}/competiciones`,
        icon: Users,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
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
