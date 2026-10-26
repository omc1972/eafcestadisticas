import * as React from "react"
import {
  HacceTheme, HacceLogo, ThemeToggle, Toaster, TooltipProvider,
  SidebarProvider, Sidebar, SidebarHeader, SidebarContent, SidebarGroup, SidebarGroupLabel,
  SidebarGroupContent, SidebarMenu, SidebarMenuItem, SidebarMenuButton, SidebarInset, SidebarTrigger,
  Separator, Input, Badge,
} from "@/hacce-ui"
import { entries } from "@/showcase/registry"

const GROUPS = ["Patrones", "Marca", "Acciones", "Formularios", "Datos", "Avisos", "Superposiciones", "Navegación", "Estructura"]

function useHash(): [string, (v: string) => void] {
  const read = () => decodeURIComponent(window.location.hash.slice(1)) || "PatronApp"
  const [hash, setHash] = React.useState(read)
  React.useEffect(() => {
    const on = () => setHash(read())
    window.addEventListener("hashchange", on)
    return () => window.removeEventListener("hashchange", on)
  }, [])
  return [hash, (v) => { window.location.hash = v }]
}

export function App() {
  const [current, go] = useHash()
  const [q, setQ] = React.useState("")
  const entry = entries.find((e) => e.name === current) ?? entries[0]
  const visible = entries.filter((e) => e.name.toLowerCase().includes(q.toLowerCase()))
  const Current = entry.Component

  return (
    <HacceTheme>
      <TooltipProvider>
        <SidebarProvider>
          <Sidebar>
            <SidebarHeader className="gap-3 px-4 py-4">
              <div className="flex items-center gap-2">
                <HacceLogo height={24} />
                <Badge variant="outline">UI</Badge>
              </div>
              <Input placeholder="Buscar componente" value={q} onChange={(e) => setQ(e.target.value)} aria-label="Buscar componente" />
            </SidebarHeader>
            <SidebarContent>
              {GROUPS.map((g) => {
                const items = visible.filter((e) => e.group === g)
                if (!items.length) return null
                return (
                  <SidebarGroup key={g}>
                    <SidebarGroupLabel>{g}</SidebarGroupLabel>
                    <SidebarGroupContent>
                      <SidebarMenu>
                        {items.map((e) => (
                          <SidebarMenuItem key={e.name}>
                            <SidebarMenuButton isActive={e.name === entry.name} onClick={() => go(e.name)}>
                              <span>{e.page ? e.name.replace(/^Patron/, "") : e.name}</span>
                            </SidebarMenuButton>
                          </SidebarMenuItem>
                        ))}
                      </SidebarMenu>
                    </SidebarGroupContent>
                  </SidebarGroup>
                )
              })}
            </SidebarContent>
          </Sidebar>
          <SidebarInset>
            <header className="bg-background sticky top-0 z-10 flex h-14 items-center gap-3 border-b px-4">
              <SidebarTrigger />
              <Separator orientation="vertical" className="h-5" />
              <div className="min-w-0">
                <h1 className="truncate text-sm font-semibold">{entry.page ? entry.name.replace(/^Patron/, "Patrón: ") : entry.name}</h1>
                <p className="text-muted-foreground truncate text-xs">{entry.subtitle || entry.group}</p>
              </div>
              <div className="ml-auto"><ThemeToggle /></div>
            </header>
            <main className={entry.page ? "p-6" : "mx-auto w-full max-w-4xl p-6"}>
              <div className={entry.page ? "" : "rounded-xl border p-8"}>
                <Current key={entry.name} />
              </div>
            </main>
          </SidebarInset>
        </SidebarProvider>
        <Toaster position="bottom-right" />
      </TooltipProvider>
    </HacceTheme>
  )
}
