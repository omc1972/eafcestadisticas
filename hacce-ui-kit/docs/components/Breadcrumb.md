# Breadcrumb

Ruta de migas de pan hasta la página actual.

## Cuándo usarlo

- Pantallas de segundo nivel o más. La última miga es `BreadcrumbPage`, sin enlace.

## Qué tienes que pasarle

`Breadcrumb`, `BreadcrumbList`, `BreadcrumbItem`, `BreadcrumbLink` (`asChild` para el enlace del router), `BreadcrumbPage`, `BreadcrumbSeparator` y `BreadcrumbEllipsis`.

## Ejemplo

```tsx
<Breadcrumb><BreadcrumbList>
  <BreadcrumbItem><BreadcrumbLink href="/">Inicio</BreadcrumbLink></BreadcrumbItem>
  <BreadcrumbSeparator />
  <BreadcrumbItem><BreadcrumbPage>Solicitudes</BreadcrumbPage></BreadcrumbItem>
</BreadcrumbList></Breadcrumb>
```
