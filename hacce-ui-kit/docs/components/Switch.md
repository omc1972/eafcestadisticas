# Switch

Interruptor para activar o desactivar algo que se aplica al momento.

## Cuándo usarlo

- Ajustes y preferencias (“Notificaciones activadas”). Si hace falta pulsar Guardar, usa `Checkbox`.

## Qué tienes que pasarle

`checked` / `defaultChecked`, `onCheckedChange`, `disabled`, `id`.

## Ejemplo

```tsx
<Switch id="avisos" defaultChecked />
<Label htmlFor="avisos">Notificaciones activadas</Label>
```
