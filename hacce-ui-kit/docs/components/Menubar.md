# Menubar

Barra de menús tipo aplicación de escritorio.

## Cuándo usarlo

- Herramientas densas (editores, informes) con muchas acciones agrupadas.

## Qué tienes que pasarle

`Menubar`, `MenubarMenu`, `MenubarTrigger`, `MenubarContent`, `MenubarItem`, `MenubarCheckboxItem`, `MenubarRadioGroup` / `RadioItem`, `MenubarSeparator`, `MenubarShortcut`, `MenubarSub`…

## Ejemplo

```tsx
<Menubar>
  <MenubarMenu>
    <MenubarTrigger>Archivo</MenubarTrigger>
    <MenubarContent><MenubarItem>Exportar a PDF</MenubarItem></MenubarContent>
  </MenubarMenu>
</Menubar>
```
