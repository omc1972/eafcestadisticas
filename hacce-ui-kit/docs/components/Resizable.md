# Resizable

Paneles que el usuario puede redimensionar arrastrando.

## Cuándo usarlo

- Vistas maestro-detalle (bandeja y detalle) en escritorio.

## Qué tienes que pasarle

`ResizablePanelGroup` (`direction`: `horizontal` | `vertical`), `ResizablePanel` (`defaultSize`, `minSize`) y `ResizableHandle` (`withHandle`).

## Ejemplo

```tsx
<ResizablePanelGroup direction="horizontal">
  <ResizablePanel defaultSize={35}>Bandeja</ResizablePanel>
  <ResizableHandle withHandle />
  <ResizablePanel>Detalle</ResizablePanel>
</ResizablePanelGroup>
```
