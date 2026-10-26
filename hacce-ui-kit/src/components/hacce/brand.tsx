import * as React from "react"
import { Moon, Sun } from "lucide-react"
import { ICONS, LOGOS, hacceIconNames } from "@/components/hacce/data"
import { useAppearance } from "@/components/hacce/theme"
import { Button } from "@/components/ui/button"
import { ToggleGroup, ToggleGroupItem } from "@/components/ui/toggle-group"
import { Tooltip, TooltipContent, TooltipTrigger } from "@/components/ui/tooltip"
import { cn } from "@/lib/utils"

export { hacceIconNames }
export type HacceIconName = (typeof hacceIconNames)[number]

export function HacceIcon({ name, size = 24, label, className, ...props }: React.SVGProps<SVGSVGElement> & { name: HacceIconName; size?: number; label?: string }) {
  const entry = ICONS[name]
  if (!entry) return null
  const [viewBox, body] = entry
  return (
    <svg width={size} height={size} viewBox={viewBox} role={label ? "img" : undefined} aria-label={label} aria-hidden={label ? undefined : true}
      focusable="false" data-slot="hacce-icon" className={cn("shrink-0", className)} {...props}
      dangerouslySetInnerHTML={{ __html: (label ? `<title>${label.replace(/[<&]/g, "")}</title>` : "") + body }} />
  )
}

export function HacceLogo({ variant = "horizontal", height = 32, className, ...props }: React.SVGProps<SVGSVGElement> & { variant?: "horizontal" | "vertical"; height?: number }) {
  const [viewBox, body] = LOGOS[variant]
  const [, , w, h] = viewBox.split(" ").map(Number)
  return (
    <svg height={height} width={(height * w) / h} viewBox={viewBox} role="img" aria-label="Hacce" data-slot="hacce-logo"
      className={cn("shrink-0", className)} {...props} dangerouslySetInnerHTML={{ __html: "<title>Hacce</title>" + body }} />
  )
}

export function ThemeToggle({ variant = "segmented", labels = { light: "Claro", dark: "Oscuro", system: "Sistema" }, className }: {
  variant?: "segmented" | "icon"; labels?: { light: string; dark: string; system: string }; className?: string
}) {
  const { appearance, resolved, setAppearance } = useAppearance()
  if (variant === "icon") {
    const next = resolved === "dark" ? "light" : "dark"
    const text = `Cambiar a modo ${(next === "dark" ? labels.dark : labels.light).toLowerCase()}`
    return (
      <Tooltip>
        <TooltipTrigger asChild>
          <Button variant="ghost" size="icon" aria-label={text} onClick={() => setAppearance(next)} className={className}>
            {resolved === "dark" ? <Sun aria-hidden /> : <Moon aria-hidden />}
          </Button>
        </TooltipTrigger>
        <TooltipContent>{text}</TooltipContent>
      </Tooltip>
    )
  }
  return (
    <ToggleGroup type="single" variant="outline" size="sm" value={appearance} aria-label="Modo de color" className={className}
      onValueChange={(v) => v && setAppearance(v as any)}>
      <ToggleGroupItem value="light" className="px-3">{labels.light}</ToggleGroupItem>
      <ToggleGroupItem value="dark" className="px-3">{labels.dark}</ToggleGroupItem>
      <ToggleGroupItem value="system" className="px-3">{labels.system}</ToggleGroupItem>
    </ToggleGroup>
  )
}
