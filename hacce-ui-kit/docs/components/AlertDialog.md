# AlertDialog

Confirmación obligatoria antes de una acción que no se puede deshacer.

## Cuándo usarlo

- Borrar, anular o enviar algo definitivo. Pregunta en el título y explica la consecuencia.
- El botón de acción repite el verbo (“Eliminar solicitud”) y, si destruye, usa `buttonVariants({ variant: "destructive" })`.

## Qué tienes que pasarle

`AlertDialog`, `AlertDialogTrigger`, `AlertDialogContent`, `AlertDialogHeader`, `AlertDialogTitle`, `AlertDialogDescription`, `AlertDialogFooter`, `AlertDialogCancel` y `AlertDialogAction`.

## Ejemplo

```tsx
<AlertDialog>
  <AlertDialogTrigger asChild><Button variant="destructive">Eliminar solicitud</Button></AlertDialogTrigger>
  <AlertDialogContent>
    <AlertDialogHeader><AlertDialogTitle>¿Eliminar la solicitud?</AlertDialogTitle><AlertDialogDescription>Esta acción no se puede deshacer.</AlertDialogDescription></AlertDialogHeader>
    <AlertDialogFooter><AlertDialogCancel>Cancelar</AlertDialogCancel><AlertDialogAction className={buttonVariants({ variant: "destructive" })}>Eliminar solicitud</AlertDialogAction></AlertDialogFooter>
  </AlertDialogContent>
</AlertDialog>
```
