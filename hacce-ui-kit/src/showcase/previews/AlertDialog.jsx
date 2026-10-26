// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { AlertDialog, AlertDialogTrigger, AlertDialogContent, AlertDialogHeader, AlertDialogTitle, AlertDialogDescription, AlertDialogFooter, AlertDialogCancel, AlertDialogAction, Button, buttonVariants } = HacceUI
export default function Preview() {
  return (<AlertDialog defaultOpen>
  <AlertDialogTrigger asChild><Button variant="destructive">Eliminar solicitud</Button></AlertDialogTrigger>
  <AlertDialogContent>
    <AlertDialogHeader><AlertDialogTitle>¿Eliminar la solicitud?</AlertDialogTitle><AlertDialogDescription>Se borrará la solicitud de vacaciones del 03/11/2026. Esta acción no se puede deshacer.</AlertDialogDescription></AlertDialogHeader>
    <AlertDialogFooter><AlertDialogCancel>Cancelar</AlertDialogCancel><AlertDialogAction className={buttonVariants({ variant: "destructive" })}>Eliminar solicitud</AlertDialogAction></AlertDialogFooter>
  </AlertDialogContent>
</AlertDialog>)
}
