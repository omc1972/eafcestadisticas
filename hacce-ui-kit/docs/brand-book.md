Hacce UI es la librería común de interfaz para las aplicaciones internas de Hacce y para las apps que hacemos para clientes. Son los componentes de **shadcn/ui** (estilo new-york, shadcn@3.4.0, sobre Radix UI y Tailwind CSS 4) con la marca Hacce aplicada: azul, negro y blanco, Inter y Oswald, esquinas contenidas y modo claro y oscuro de serie.

## Principios

- **Clara y sobria.** Fondo `background` (blanco), texto `foreground` (`hacce-negro` #000000) y el azul `primary` (`hacce-azul-oscuro` #2a6b7c) solo donde hay acción o selección.
- **Una sola voz de color.** El acento es siempre el azul Hacce. No cambies `primary` por app ni por pantalla, y no pintes a mano con colores de la paleta de Tailwind (`bg-blue-600`, `text-gray-500`): usa los tokens.
- **El coral casi no existe.** `hacce-coral` solo en un detalle de ilustración o marketing. Nunca en botones, fondos, titulares ni errores.
- **Igual en todas las apps.** Misma estructura (`Sidebar` a la izquierda con el logo arriba, `ThemeToggle` en la cabecera o el menú de usuario), mismos patrones de formulario (`Field`), tabla (`Table`) y diálogo (`Dialog`, `AlertDialog`).

## Contenido y tono

- Escribe en español, de tú, frases cortas y en positivo: “Guarda los cambios”, “No se ha podido enviar. Inténtalo de nuevo.”
- Mayúscula solo al inicio (estilo frase) en títulos, botones y pestañas: “Solicitudes recientes”, no “Solicitudes Recientes”. La única excepción es el titular display en Oswald, que va en mayúsculas.
- Botones con verbo y objeto cuando haya ambigüedad: “Eliminar solicitud”, no “Aceptar”.
- Errores que dicen cómo arreglarlo: “Escribe un teléfono de 9 cifras.”
- Sin emoji en la interfaz. Fechas `dd/mm/aaaa`, miles con punto (1.248), decimales con coma y espacio antes de `%` (60 %).

## Color

- **Marca:** `hacce-negro`, `hacce-azul-oscuro`, `hacce-azul-claro` y `hacce-coral`, tal cual vienen en la guía. En Tailwind: `bg-hacce-azul-claro`, `text-hacce-negro`…
- **Variables de shadcn:** `background`/`foreground`, `card`, `popover`, `primary`, `secondary`, `muted`, `accent`, `destructive`, `border`, `input`, `ring`, `chart-1`…`chart-5` y `sidebar-*`. Cada una con su pareja `-foreground` para el texto que va encima. En Tailwind son `bg-primary`, `text-muted-foreground`, `border-input`, `ring-ring`…
- **`accent`** es el azul de marca muy suave (#e6f4f8): hover y elemento activo de menús, listas y botones `ghost`. **`muted`** es el gris casi blanco de cabeceras de tabla y pistas.
- **`hacce-azul-claro`** solo en superficies e ilustración (bandas, portadas). Sobre blanco no llega a 3:1, así que nunca para texto ni para bordes de controles.
- **Estados:** `destructive` para error y acciones destructivas; `success` y `warning` (añadidos de Hacce, shadcn no los trae) para aprobado y aviso. Acompaña siempre el color con un icono o una palabra.
- **Contraste (WCAG AA):** `foreground` da 21:1 en claro y 16,3:1 en oscuro; `muted-foreground` 5,9:1 y 9,1:1; `primary` con su `primary-foreground` 6,0:1 y 9,9:1; el borde `input` de los campos 3,3:1 y 4,5:1; el foco `ring` 6,0:1 y 4,1:1.

## Modo claro y oscuro

- Toda app arranca en `"system"` (sigue al sistema operativo). El usuario lo cambia con `ThemeToggle` y su elección se recuerda en localStorage (`hacce-appearance`).
- `HacceTheme` escribe `data-theme="dark"` (y la clase `.dark`) en `<html>`; los tokens cambian solos y las clases `dark:` de shadcn funcionan con cualquiera de los dos.
- En oscuro el fondo es #111111, las tarjetas #191919 y los menús #222222. **`primary` pasa a azul claro #78c7dd con texto #111111**: el azul oscuro sobre #111111 solo daría 3,1:1 en enlaces.
- No escribas colores fijos (`#fff`, `#000`, `bg-white`) en tu CSS ni en tus clases: usa `bg-background`, `text-foreground` o `var(--primary)`, que cambian con el modo.

## Tipografía

- **Inter** (Light 300, Regular 400 y SemiBold 600) para toda la interfaz (`font-sans`). Los `font-medium` (500) y `font-bold` (700) que usa shadcn se pintan con SemiBold, el único peso fuerte de la guía.
- Interfaz densa en `text-sm` (14/20), que es el tamaño de botones, campos, menús y tablas. Cuerpo de lectura en `text-base` (16/24). Notas en `text-xs` (12/16).
- Títulos: h1 de página `text-4xl font-semibold tracking-tight` (`titulo-h1`), h2 `text-3xl` (`titulo-h2`), h3 `text-2xl` (`titulo-h3`), h4 `text-xl` (`titulo-h4`). Títulos de tarjeta y diálogo, los de `CardTitle` y `DialogTitle`.
- `font-light` (`peso-light`) para cifras grandes en paneles: “1.248 solicitudes”. Números en tablas con `tabular-nums`.
- **Oswald Light** en MAYÚSCULAS (`display-secundaria`, utilidad `font-display`) solo en titulares editoriales o de marketing de las apps de cliente. Nunca en pantallas de trabajo.

## Espaciado, radios y sombras

- La unidad es `spacing` = 4px: `gap-2` (8px) entre controles relacionados, `gap-4`/`p-4` (16px) en formularios, `p-6` (24px) dentro de las tarjetas, `gap-6`/`gap-8` entre bloques y secciones. No uses valores arbitrarios (`p-[13px]`).
- Radios contenidos, como los recuadros de la guía: `rounded-md` 4,5px en botones, campos y menús; `rounded-lg` 6px en diálogos, alertas y avisos; `rounded-xl` 9px en tarjetas; `rounded-sm` 3px en elementos pequeños.
- Sombras de Tailwind solo en lo que flota: `shadow-xs` en controles, `shadow-sm` en tarjetas, `shadow-md` en menús y popovers, `shadow-lg` en diálogos. Las tarjetas se separan por el borde, no por la sombra.
- Foco: borde `ring` y anillo de 3px `ring-ring/50`, de serie en todos los controles. No lo quites.

## Iconografía

- Iconos de marca con `HacceIcon` (los 24 de la guía, trazo de 2px) para navegación, acciones principales y estados vacíos. Heredan el color del texto.
- Los componentes traen sus propios glifos de **lucide-react** (chevrons, aspas, marcas): no los sustituyas. Si un icono de marca no existe, usa el de lucide equivalente (los más comunes están en `HacceUI.Icons`) al mismo tamaño y color.
- Un icono sin texto lleva `aria-label` en su botón y un `Tooltip`. Ningún emoji hace de icono.

## Logo

- `HacceLogo` horizontal en la cabecera o arriba de la `Sidebar`, a 22–32px de alto. La versión vertical solo en login o splash.
- Toma `currentColor`: negro en claro, casi blanco en oscuro. No lo recolorees ni lo deformes. Las versiones en archivo están en Logos.

## Patrones

Las páginas del grupo Patrones (PatronApp, PatronPanel, PatronFormulario y PatronLogin) montan estos patrones completos con los componentes reales: cópialas como punto de partida.

- **App interna:** `SidebarProvider` › `Sidebar` (logo, grupos, usuario) + `SidebarInset` con una cabecera (`SidebarTrigger`, `Breadcrumb`, `ThemeToggle`) y el contenido en `max-w-5xl` con `p-6`.
- **Formulario:** `FieldSet` › `FieldGroup` › `Field` con `FieldLabel`, control y `FieldDescription`/`FieldError`. Con validación, `Form` + `useForm`. Botón principal a la izquierda al final.
- **Listado:** `Table` con `Badge` de estado, acciones de fila en `DropdownMenu`, `Pagination` debajo y `Empty` cuando no hay datos.
- **Confirmar o borrar:** `AlertDialog`. **Avisar de que algo salió bien:** `toast()` de `Sonner`. **Aviso fijo en la página:** `Alert`.

## Cómo se usa en código

1. En una app nueva con shadcn/ui, copia los valores de este sistema: los tokens (`tokens.css` o `tokens.json`) en tu `globals.css`, las fuentes de `fonts/` y los componentes con `npx shadcn@latest add`. Los componentes de Hacce son los de shadcn sin cambios de estructura, con textos en español, el calendario en lunes y los avatares en `accent`.
2. Para prototipos y vistas rápidas, carga el bundle del sistema (React 18.3.1, `components/bundle.css` y `components/bundle.js`): todo queda en `window.HacceUI`, con utilidades de Tailwind de maquetación (`flex`, `grid`, `gap-*`, `p-*`, `text-*`…) ya compiladas.
3. Envuelve la app una vez en `<HacceTheme>` y coloca un `<Toaster />` en la raíz.

```tsx
import { HacceTheme, ThemeToggle, HacceLogo, Button, Toaster } from "@hacce/ui";

export function App() {
  return (
    <HacceTheme>
      <header className="flex items-center justify-between border-b px-4 py-3">
        <HacceLogo height={28} />
        <ThemeToggle />
      </header>
      <main className="mx-auto max-w-5xl p-6">
        <h1 className="text-4xl font-semibold tracking-tight">Portal del empleado</h1>
        <Button className="mt-4">Nueva solicitud</Button>
      </main>
      <Toaster />
    </HacceTheme>
  );
}
```

- `HacceTheme` acepta `defaultAppearance` (`"system"`, `"light"` o `"dark"`) y `appearance` para fijar el modo. `useAppearance()` lee o cambia el modo desde cualquier componente.
- En CSS propio usa las variables (`var(--primary)`, `var(--muted-foreground)`, `var(--radius-md)`) y nunca valores fijos.
- La referencia de cada componente está en su ficha y en la documentación de shadcn/ui (https://ui.shadcn.com/docs/components).
