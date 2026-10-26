# Tabs

Pestañas para cambiar entre vistas del mismo contenido.

## Cuándo usarlo

- 2–5 vistas hermanas (Pendientes, Aprobadas, Historial). Para navegar entre páginas usa enlaces o `NavigationMenu`.

## Qué tienes que pasarle

`Tabs` (`value` / `defaultValue`, `onValueChange`), `TabsList`, `TabsTrigger value` y `TabsContent value`.

## Ejemplo

```tsx
<Tabs defaultValue="pendientes">
  <TabsList><TabsTrigger value="pendientes">Pendientes</TabsTrigger><TabsTrigger value="aprobadas">Aprobadas</TabsTrigger></TabsList>
  <TabsContent value="pendientes">…</TabsContent>
</Tabs>
```
