# Dialog

Ventana modal para una tarea corta que no saca de la página.

## Cuándo usarlo

- Editar un dato, confirmar con información extra, formularios breves (1–4 campos).
- Título que nombra la tarea, botón principal a la derecha y “Cancelar” a su izquierda.

## Qué tienes que pasarle

`Dialog` (`open`, `onOpenChange`), `DialogTrigger asChild`, `DialogContent` (`showCloseButton`), `DialogHeader`, `DialogTitle`, `DialogDescription`, `DialogFooter` y `DialogClose`.

## Ejemplo

```tsx
<Dialog>
  <DialogTrigger asChild><Button variant="outline">Editar perfil</Button></DialogTrigger>
  <DialogContent>
    <DialogHeader><DialogTitle>Editar perfil</DialogTitle><DialogDescription>Cambia tus datos de contacto.</DialogDescription></DialogHeader>
    <DialogFooter><DialogClose asChild><Button variant="outline">Cancelar</Button></DialogClose><Button>Guardar cambios</Button></DialogFooter>
  </DialogContent>
</Dialog>
```
