# Command

Buscador de acciones y elementos con teclado (cmdk).

## Cuándo usarlo

- Buscador global (⌘K) con `CommandDialog`, o selectores con búsqueda dentro de un `Popover`.

## Qué tienes que pasarle

`Command` o `CommandDialog` (`open`, `onOpenChange`, `title`, `description`), `CommandInput`, `CommandList`, `CommandEmpty`, `CommandGroup heading`, `CommandItem` (`onSelect`), `CommandSeparator`, `CommandShortcut`.

## Ejemplo

```tsx
<Command>
  <CommandInput placeholder="Busca una acción" />
  <CommandList>
    <CommandEmpty>No hay resultados.</CommandEmpty>
    <CommandGroup heading="Acciones"><CommandItem>Pedir vacaciones</CommandItem></CommandGroup>
  </CommandList>
</Command>
```
