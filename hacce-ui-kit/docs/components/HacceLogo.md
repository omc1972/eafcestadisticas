# HacceLogo

El logo Hacce (versión 1 horizontal o vertical) como componente que sigue el color del texto.

## Cuándo usarlo

- Horizontal en la cabecera de todas las apps, a 22–32 px de alto, a la izquierda.
- Vertical solo en espacios cuadrados: login, splash, portadas.
- Toma `currentColor`: `foreground` (negro #000000) en claro y casi blanco en oscuro. Sobre `hacce-azul-claro` fuerza `text-hacce-negro`.

## Ten en cuenta

- No lo recolorees en azul ni en coral, no lo deformes y no le añadas claims.

## Qué tienes que pasarle

`variant` (`"horizontal"` por defecto o `"vertical"`), `height` en px (el ancho se calcula) y `className`.

## Ejemplo

```tsx
<HacceLogo height={28} />
<HacceLogo variant="vertical" height={72} />
```
