# Sonner

Avisos breves (toasts) que confirman una acción sin interrumpir.

## Cuándo usarlo

- Confirmaciones (“Cambios guardados”) y errores recuperables. Lo que exige decisión va en `AlertDialog`.
- Coloca un único `<Toaster />` en la raíz de la app y llama a `toast()` desde cualquier sitio.

## Qué tienes que pasarle

`Toaster` (`position`, `richColors`, `duration`…; sigue el modo claro u oscuro de Hacce) y `toast(mensaje, { description, action })`, `toast.success`, `toast.error`.

## Ejemplo

```tsx
<Toaster position="bottom-right" />
toast.success("Cambios guardados")
```
