# Alert

Mensaje destacado dentro de la página.

## Cuándo usarlo

- Información que afecta a toda la pantalla: un cambio, un aviso, un error al cargar.
- Siempre con icono y título; `destructive` para errores.

## Qué tienes que pasarle

`Alert` (`variant`: `default` | `destructive`) con un icono, `AlertTitle` y `AlertDescription`.

## Ejemplo

```tsx
<Alert variant="destructive">
  <CircleAlert />
  <AlertTitle>No se ha podido enviar</AlertTitle>
  <AlertDescription>Revisa tu conexión e inténtalo de nuevo.</AlertDescription>
</Alert>
```
