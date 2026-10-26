# Carousel

Carrusel de tarjetas o imágenes con botones anterior y siguiente.

## Cuándo usarlo

- Avisos y destacados en portadas de apps de cliente. Nunca para información imprescindible.

## Qué tienes que pasarle

`Carousel` (`opts` de Embla, `orientation`, `setApi`), `CarouselContent`, `CarouselItem` (`className="basis-1/3"` para varias por vista), `CarouselPrevious` y `CarouselNext`.

## Ejemplo

```tsx
<Carousel>
  <CarouselContent><CarouselItem>…</CarouselItem></CarouselContent>
  <CarouselPrevious /><CarouselNext />
</Carousel>
```
