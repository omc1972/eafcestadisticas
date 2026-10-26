# DropdownMenu

Menú de acciones que se abre desde un botón.

## Cuándo usarlo

- Acciones de fila en tablas, menú de usuario, “Más opciones”.
- La acción destructiva va al final, separada y con `variant="destructive"`.

## Qué tienes que pasarle

`DropdownMenu`, `DropdownMenuTrigger asChild`, `DropdownMenuContent` (`align`), `DropdownMenuLabel`, `DropdownMenuItem` (`variant`: `default` | `destructive`, `inset`), `DropdownMenuCheckboxItem`, `DropdownMenuRadioGroup` / `RadioItem`, `DropdownMenuSeparator`, `DropdownMenuShortcut`, `DropdownMenuSub`…

## Ejemplo

```tsx
<DropdownMenu>
  <DropdownMenuTrigger asChild><Button variant="outline">Mi cuenta</Button></DropdownMenuTrigger>
  <DropdownMenuContent>
    <DropdownMenuItem>Perfil</DropdownMenuItem>
    <DropdownMenuSeparator />
    <DropdownMenuItem variant="destructive">Cerrar sesión</DropdownMenuItem>
  </DropdownMenuContent>
</DropdownMenu>
```
