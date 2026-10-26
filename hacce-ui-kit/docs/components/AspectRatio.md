# AspectRatio

Mantiene una proporción fija para imágenes, vídeos o mapas.

## Cuándo usarlo

- Imágenes de portada (16/9), miniaturas (1/1).

## Qué tienes que pasarle

`ratio` (por ejemplo `16 / 9`) y el contenido.

## Ejemplo

```tsx
<AspectRatio ratio={16 / 9}><img src={portada} alt="" className="size-full rounded-lg object-cover" /></AspectRatio>
```
