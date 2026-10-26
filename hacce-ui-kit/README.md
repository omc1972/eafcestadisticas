# Hacce UI · kit para desarrollo

Este proyecto contiene el design system Hacce UI listo para aplicar y probar: los componentes de **shadcn/ui** (estilo new-york) con la marca Hacce, sobre **React 19, Tailwind CSS 4 y Vite**. Incluye un banco de pruebas con los 56 componentes y cuatro pantallas de ejemplo (app interna, panel, formulario y acceso), en modo claro y oscuro.

Referencia visual y guías de uso: el design system Hacce UI en Claude (https://claude.ai/artifact/4EuWooaQetkWRBPw1mL8Cj). Copia offline del brand book en `docs/brand-book.md` y una ficha por componente en `docs/components/`.

## Arrancar

Hace falta Node 20 o superior.

```bash
npm install
npm run dev        # http://localhost:5173
npm run build      # comprobación de tipos + build de producción
```

El menú lateral lista patrones y componentes; el selector de la cabecera cambia entre claro, oscuro y sistema. Cada vista tiene su enlace (`/#Button`, `/#PatronApp`…).

## Estructura

| Ruta | Qué hay |
| --- | --- |
| `src/styles/globals.css` | **Lo más importante.** Fuentes, tokens Hacce (claro y oscuro) y su enlace con Tailwind/shadcn. |
| `src/components/ui/` | Los 53 componentes de shadcn/ui con los ajustes de Hacce (ver abajo). |
| `src/components/hacce/` | `HacceTheme` (modo claro/oscuro), `ThemeToggle`, `HacceLogo` y `HacceIcon`. |
| `src/hacce-ui.ts` | Punto de entrada único: `import { Button, HacceLogo } from "@/hacce-ui"`. |
| `src/showcase/` | El banco de pruebas: una vista por componente y los patrones. No hace falta llevarlo a producción. |
| `public/fonts/` | Inter Light, Regular y SemiBold, y Oswald Light (woff2). |
| `public/brand/` | Logos (positivo, vertical y negativos) e iconos de la guía en SVG. |
| `tokens/tokens.json` | Los tokens en formato del design system (fuente de verdad de los valores). |
| `components.json` | Configuración de shadcn para que `npx shadcn add` funcione en este proyecto. |

## Aplicarlo en una app

**App nueva:** parte de este proyecto y borra `src/showcase/` y `src/App.tsx`.

**App existente con shadcn/ui y Tailwind 4:**

1. Sustituye tu `globals.css` por `src/styles/globals.css` (o copia sus bloques: `@font-face`, tokens, `@custom-variant dark`, `@theme` y `@layer base`).
2. Copia `public/fonts/` a tu carpeta pública. Las rutas son `/fonts/...`; ajústalas si sirves los estáticos en otra ruta.
3. Copia `src/components/ui/` encima de tus componentes (o añade solo los que falten) y `src/components/hacce/`.
4. Envuelve la app una vez y añade el Toaster:

```tsx
import { HacceTheme, Toaster, TooltipProvider } from "@/hacce-ui"

<HacceTheme>
  <TooltipProvider>
    <App />
    <Toaster position="bottom-right" />
  </TooltipProvider>
</HacceTheme>
```

5. Copia el script de `index.html` que aplica el modo guardado antes de pintar, para evitar el parpadeo al cargar en oscuro.

**Next.js:** marca `src/components/hacce/theme.tsx` y `brand.tsx` con `"use client"` y pon el script anti-parpadeo en el `<head>` del layout. Si ya usas `next-themes`, configúralo con `attribute="data-theme"`: los tokens también responden a la clase `.dark`.

## Qué cambia respecto a shadcn/ui original

Para poder actualizar shadcn más adelante sin perder la marca, estos son todos los cambios:

- **Tokens** (`globals.css`): todos los colores, radios y fuentes son de Hacce. Añadidos `success`, `warning` y los cuatro colores de marca (`hacce-negro`, `hacce-azul-oscuro`, `hacce-azul-claro`, `hacce-coral`). Utilidad `font-display` (Oswald Light en mayúsculas).
- **Modo oscuro**: se activa con `data-theme="dark"` en `<html>` (o la clase `.dark`). En oscuro `primary` es azul claro #78c7dd con texto #111111, porque el azul oscuro de marca no llega a 4,5:1 sobre el fondo #111111.
- **Borde de campos** (`--input`): gris #8d8d8d en claro para llegar al 3:1 que exige un control; el de shadcn es más claro.
- **Textos en español**: `dialog.tsx` y `sheet.tsx` («Cerrar»), `pagination.tsx` («Anterior», «Siguiente», etiquetas), `breadcrumb.tsx` («Más»), `carousel.tsx`, `command.tsx`, `sidebar.tsx` y `spinner.tsx` («Cargando»).
- **`calendar.tsx`**: idioma español y semana empezando en lunes por defecto (`locale`, `weekStartsOn`).
- **`avatar.tsx`**: las iniciales van sobre `accent` (azul suave de marca) en lugar de `muted`.
- **`sonner.tsx`**: lee el modo de `HacceTheme` en lugar de `next-themes`.
- **Pesos**: solo hay Inter 300, 400 y 600; `font-medium` y `font-bold` se pintan con SemiBold (la `@font-face` de SemiBold cubre 500–900).

Si añades un componente nuevo con `npx shadcn add`, tomará los tokens automáticamente. Revisa solo si trae textos en inglés.

## Pruebas recomendadas

- Recorre todas las vistas en claro, oscuro y sistema; cambia de modo con una vista abierta.
- Teclado: navega con Tab por formularios, menús, diálogos y el calendario; el foco debe verse siempre (borde y anillo azul).
- Lector de pantalla (VoiceOver o NVDA) en Dialog, AlertDialog, Select, Tabs y los botones con solo icono.
- Móvil (≤ 768 px): la barra lateral pasa a panel; prueba Drawer, Sheet y las tablas con scroll horizontal.
- Zoom del navegador al 200 %: nada debe cortarse ni solaparse.
- Contraste: comprueba con la herramienta de tu navegador los textos `muted-foreground` y los estados `destructive`, `success` y `warning` en los dos modos.
- Fuentes: con la caché vacía, confirma que cargan Inter y Oswald y no la fuente del sistema.

## Versiones

React 19, Tailwind CSS 4, Vite 6, componentes de shadcn/ui 3.4.0 (new-york-v4) sobre Radix UI. Todas las dependencias están fijadas a una versión exacta en `package.json` y `package-lock.json`.
