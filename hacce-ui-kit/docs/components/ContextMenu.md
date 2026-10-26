# ContextMenu

Menú que aparece con clic derecho sobre un elemento.

## Cuándo usarlo

- Atajo para usuarios avanzados; las mismas acciones deben estar también en un botón visible.

## Qué tienes que pasarle

`ContextMenu`, `ContextMenuTrigger`, `ContextMenuContent`, `ContextMenuItem` (`variant`), `ContextMenuSeparator`, `ContextMenuShortcut`, `ContextMenuSub`…

## Ejemplo

```tsx
<ContextMenu>
  <ContextMenuTrigger>Documento</ContextMenuTrigger>
  <ContextMenuContent><ContextMenuItem>Descargar</ContextMenuItem></ContextMenuContent>
</ContextMenu>
```
