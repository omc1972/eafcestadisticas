# Button

Botón de shadcn/ui con la marca Hacce: una acción por botón.

## Cuándo usarlo

- `default` (azul de marca) para la acción principal: una sola por pantalla o diálogo.
- `outline` y `secondary` para acciones secundarias, `ghost` en barras y tablas, `link` para navegación dentro de texto.
- `destructive` solo para borrar o anular, con verbo y objeto: “Eliminar solicitud”.
- Con icono, ponlo antes del texto; los SVG se dimensionan solos a 16 px.

## Ten en cuenta

- No uses “Aceptar” u “OK”: nombra la acción.
- Para estados de carga, desactiva el botón y añade `<Spinner />` antes del texto.

## Qué tienes que pasarle

`variant` (`default` | `destructive` | `outline` | `secondary` | `ghost` | `link`), `size` (`default` | `sm` | `lg` | `icon` | `icon-sm` | `icon-lg`), `asChild` para pintar un `<a>` u otro elemento, y los atributos de `<button>`. `buttonVariants()` da las mismas clases para enlaces.

## Ejemplo

```tsx
<Button>Guardar</Button>
<Button variant="outline"><HacceIcon name="descargar" size={16} /> Exportar</Button>
<Button size="icon" variant="ghost" aria-label="Añadir"><Plus /></Button>
<Button asChild variant="link"><a href="/solicitudes">Ver solicitudes</a></Button>
```
