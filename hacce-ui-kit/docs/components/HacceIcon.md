# HacceIcon

Los 24 iconos de línea de la guía Hacce, como componente que hereda el color del texto.

## Cuándo usarlo

- Navegación, acciones principales y estados vacíos: es el set de marca.
- Tamaños: 16 px junto a texto y en botones, 18–24 px en menús, 24–48 px en estados vacíos.
- Si el icono va solo (sin texto), pasa `label` o pon `aria-label` en el botón que lo contiene.

## Ten en cuenta

- Para chevrons, aspas y marcas de control dentro de los componentes se usan los iconos de lucide que ya traen; no los sustituyas.

## Qué tienes que pasarle

`name` (uno de `hacceIconNames`: carpeta, documento, inicio, usuario, usuaria, calendario, archivo, subir, descargar, nube, correcto, cerrar, comentario, imagen, video, calculadora, ajustes, escritorio, movil, velocidad, web, compra, etiqueta, papelera), `size` en px (24 por defecto), `label` y `className`.

## Ejemplo

```tsx
<Button><HacceIcon name="descargar" size={16} /> Descargar</Button>
<Button variant="ghost" size="icon" aria-label="Ajustes"><HacceIcon name="ajustes" size={18} /></Button>
```
