// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"
import * as Icons from "lucide-react"

const { SidebarProvider, Sidebar, SidebarHeader, SidebarContent, SidebarGroup, SidebarGroupLabel, SidebarGroupContent, SidebarMenu, SidebarMenuItem, SidebarMenuButton, SidebarMenuBadge, SidebarFooter, SidebarInset, SidebarTrigger,
  HacceLogo, HacceIcon, Separator, Breadcrumb, BreadcrumbList, BreadcrumbItem, BreadcrumbLink, BreadcrumbPage, BreadcrumbSeparator, ThemeToggle, Button, Card, CardHeader, CardTitle, CardDescription, CardContent, CardAction,
  Table, TableHeader, TableBody, TableRow, TableHead, TableCell, Badge, Avatar, AvatarFallback, DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator } = HacceUI
const { Ellipsis, Plus, CircleCheck } = Icons
const nav = [["inicio", "Inicio", true], ["calendario", "Vacaciones", false, "2"], ["velocidad", "Fichajes"], ["documento", "Nóminas"], ["carpeta", "Documentos"]]
const filas = [["Lucía Martín", "LM", "Vacaciones", "03/11/2026", "5", "Aprobada"], ["Jorge Ruiz", "JR", "Asuntos propios", "07/11/2026", "1", "Pendiente"], ["Ana Sanz", "AS", "Teletrabajo", "10/11/2026", "3", "Pendiente"]]
function Kpi({ t, v, d }) { return <Card className="gap-2 py-5"><CardHeader className="px-5"><CardDescription>{t}</CardDescription><CardTitle className="text-3xl font-light tabular-nums">{v}</CardTitle></CardHeader><CardContent className="text-muted-foreground px-5 text-xs">{d}</CardContent></Card> }
export default function Preview() {
  return (<SidebarProvider className="min-h-[600px] overflow-hidden rounded-lg border" style={{ "--sidebar-width": "15rem" }}>
  <Sidebar collapsible="none" className="h-auto border-r">
    <SidebarHeader className="px-4 py-4"><HacceLogo height={24} /></SidebarHeader>
    <SidebarContent>
      <SidebarGroup><SidebarGroupLabel>Mi espacio</SidebarGroupLabel><SidebarGroupContent><SidebarMenu>
        {nav.map(([i, t, a, b]) => <SidebarMenuItem key={t}><SidebarMenuButton isActive={!!a}><HacceIcon name={i} size={16} /><span>{t}</span></SidebarMenuButton>{b && <SidebarMenuBadge>{b}</SidebarMenuBadge>}</SidebarMenuItem>)}
      </SidebarMenu></SidebarGroupContent></SidebarGroup>
      <SidebarGroup><SidebarGroupLabel>Equipo</SidebarGroupLabel><SidebarGroupContent><SidebarMenu>
        <SidebarMenuItem><SidebarMenuButton><HacceIcon name="usuario" size={16} /><span>Personas</span></SidebarMenuButton></SidebarMenuItem>
        <SidebarMenuItem><SidebarMenuButton><HacceIcon name="ajustes" size={16} /><span>Ajustes</span></SidebarMenuButton></SidebarMenuItem>
      </SidebarMenu></SidebarGroupContent></SidebarGroup>
    </SidebarContent>
    <SidebarFooter className="px-3 pb-3"><div className="flex items-center gap-2 rounded-md p-2"><Avatar className="size-8"><AvatarFallback>LM</AvatarFallback></Avatar><div className="grid text-sm leading-tight"><span className="font-semibold">Lucía Martín</span><span className="text-muted-foreground text-xs">Personas · Madrid</span></div></div></SidebarFooter>
  </Sidebar>
  <SidebarInset>
    <header className="flex h-14 items-center gap-3 border-b px-6">
      <Breadcrumb><BreadcrumbList><BreadcrumbItem><BreadcrumbLink href="#">Portal</BreadcrumbLink></BreadcrumbItem><BreadcrumbSeparator /><BreadcrumbItem><BreadcrumbPage>Inicio</BreadcrumbPage></BreadcrumbItem></BreadcrumbList></Breadcrumb>
      <div className="ml-auto flex items-center gap-2"><ThemeToggle variant="icon" /></div>
    </header>
    <main className="grid gap-6 p-6">
      <div className="flex items-end justify-between gap-4"><div><h1 className="text-3xl font-semibold tracking-tight">Hola, Lucía</h1><p className="text-muted-foreground text-sm">Esto es lo que tienes pendiente esta semana.</p></div><Button><Plus /> Nueva solicitud</Button></div>
      <div className="grid grid-cols-3 gap-4"><Kpi t="Días de vacaciones" v="12" d="de 23 disponibles en 2026" /><Kpi t="Horas esta semana" v="31,5" d="de 37,5 previstas" /><Kpi t="Solicitudes por revisar" v="2" d="de tu equipo" /></div>
      <Card className="gap-0 py-0"><CardHeader className="border-b px-5 py-4 [.border-b]:pb-4"><CardTitle>Solicitudes recientes</CardTitle><CardAction><Button variant="outline" size="sm">Ver todas</Button></CardAction></CardHeader>
        <CardContent className="px-0"><Table><TableHeader><TableRow><TableHead className="pl-5">Persona</TableHead><TableHead>Tipo</TableHead><TableHead>Desde</TableHead><TableHead className="text-right">Días</TableHead><TableHead>Estado</TableHead><TableHead /></TableRow></TableHeader>
          <TableBody>{filas.map(f => <TableRow key={f[0]}><TableCell className="pl-5"><div className="flex items-center gap-2"><Avatar className="size-7"><AvatarFallback className="text-xs">{f[1]}</AvatarFallback></Avatar>{f[0]}</div></TableCell><TableCell>{f[2]}</TableCell><TableCell className="tabular-nums">{f[3]}</TableCell><TableCell className="text-right tabular-nums">{f[4]}</TableCell>
            <TableCell>{f[5] === "Aprobada" ? <Badge variant="outline" className="text-success"><CircleCheck /> Aprobada</Badge> : <Badge variant="outline">Pendiente</Badge>}</TableCell>
            <TableCell className="pr-5 text-right"><DropdownMenu><DropdownMenuTrigger asChild><Button variant="ghost" size="icon-sm" aria-label="Acciones"><Ellipsis /></Button></DropdownMenuTrigger><DropdownMenuContent align="end"><DropdownMenuItem>Ver detalle</DropdownMenuItem><DropdownMenuItem>Aprobar</DropdownMenuItem><DropdownMenuSeparator /><DropdownMenuItem variant="destructive">Rechazar</DropdownMenuItem></DropdownMenuContent></DropdownMenu></TableCell></TableRow>)}</TableBody></Table></CardContent></Card>
    </main>
  </SidebarInset>
</SidebarProvider>)
}
