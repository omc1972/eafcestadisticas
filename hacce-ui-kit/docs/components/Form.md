# Form

Formularios con validación sobre react-hook-form, con etiquetas y errores conectados.

## Cuándo usarlo

- Cuando el formulario valida campos o envía datos: une cada control con su etiqueta, ayuda y error.
- Los mensajes de error dicen cómo arreglarlo: “Escribe un teléfono de 9 cifras.”

## Qué tienes que pasarle

`Form` recibe el objeto de `useForm()` (exportado por Hacce UI); `FormField` (`control`, `name`, `render`), `FormItem`, `FormLabel`, `FormControl`, `FormDescription` y `FormMessage`.

## Ejemplo

```tsx
const form = useForm({ defaultValues: { usuario: "" } })
<Form {...form}>
  <form onSubmit={form.handleSubmit(guardar)}>
    <FormField control={form.control} name="usuario" render={({ field }) => (
      <FormItem>
        <FormLabel>Usuario</FormLabel>
        <FormControl><Input {...field} /></FormControl>
        <FormMessage />
      </FormItem>
    )} />
    <Button type="submit">Guardar</Button>
  </form>
</Form>
```
