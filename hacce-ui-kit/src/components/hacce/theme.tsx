import * as React from "react"

export type HacceAppearance = "light" | "dark" | "system"
type Ctx = { appearance: HacceAppearance; resolved: "light" | "dark"; setAppearance: (a: HacceAppearance) => void }
const HacceThemeContext = React.createContext<Ctx | null>(null)

function systemDark() {
  return typeof window !== "undefined" && !!window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches
}
function readStored(key: string | false): HacceAppearance | null {
  if (!key) return null
  try {
    const v = window.localStorage.getItem(key)
    return v === "light" || v === "dark" || v === "system" ? v : null
  } catch { return null }
}
/** Lee el modo aplicado en <html data-theme> (vale también fuera de HacceTheme). */
function readDocTheme(): "light" | "dark" {
  if (typeof document === "undefined") return "light"
  const el = document.documentElement
  return el.getAttribute("data-theme") === "dark" || el.classList.contains("dark") ? "dark" : "light"
}

export function HacceTheme({ defaultAppearance = "system", appearance, storageKey = "hacce-appearance", applyToDocument = true, children }: {
  defaultAppearance?: HacceAppearance; appearance?: HacceAppearance; storageKey?: string | false; applyToDocument?: boolean; children?: React.ReactNode
}) {
  const [chosen, setChosen] = React.useState<HacceAppearance>(() => appearance ?? readStored(storageKey) ?? defaultAppearance)
  const [dark, setDark] = React.useState(systemDark)
  const current = appearance ?? chosen
  React.useEffect(() => {
    if (typeof window === "undefined" || !window.matchMedia) return
    const mq = window.matchMedia("(prefers-color-scheme: dark)")
    const on = () => setDark(mq.matches)
    mq.addEventListener?.("change", on)
    return () => mq.removeEventListener?.("change", on)
  }, [])
  const resolved: "light" | "dark" = current === "system" ? (dark ? "dark" : "light") : current
  React.useEffect(() => {
    if (!applyToDocument) return
    const el = document.documentElement
    el.setAttribute("data-theme", resolved)
    el.classList.toggle("dark", resolved === "dark")
    el.style.colorScheme = resolved
  }, [resolved, applyToDocument])
  const setAppearance = React.useCallback((a: HacceAppearance) => {
    setChosen(a)
    if (storageKey) try { window.localStorage.setItem(storageKey, a) } catch {}
  }, [storageKey])
  const value = React.useMemo(() => ({ appearance: current, resolved, setAppearance }), [current, resolved, setAppearance])
  return <HacceThemeContext.Provider value={value}>{children}</HacceThemeContext.Provider>
}

export function useAppearance(): Ctx {
  const ctx = React.useContext(HacceThemeContext)
  if (!ctx) throw new Error("useAppearance debe usarse dentro de <HacceTheme>")
  return ctx
}

/** Modo aplicado ("light" | "dark"): del contexto si hay HacceTheme, si no de <html data-theme>. */
export function useResolvedTheme(): "light" | "dark" {
  const ctx = React.useContext(HacceThemeContext)
  const [docTheme, setDocTheme] = React.useState(readDocTheme)
  React.useEffect(() => {
    if (ctx) return
    const mo = new MutationObserver(() => setDocTheme(readDocTheme()))
    mo.observe(document.documentElement, { attributes: true, attributeFilter: ["data-theme", "class"] })
    return () => mo.disconnect()
  }, [ctx])
  return ctx ? ctx.resolved : docTheme
}
