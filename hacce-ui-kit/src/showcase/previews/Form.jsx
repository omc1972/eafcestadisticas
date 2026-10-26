// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Form, FormField, FormItem, FormLabel, FormControl, FormDescription, FormMessage, Input, Button, useForm, toast } = HacceUI
function Demo() {
  const form = useForm({ defaultValues: { usuario: "" } })
  const onSubmit = (v) => { if (v.usuario.length < 3) { form.setError("usuario", { message: "El usuario debe tener al menos 3 caracteres." }); return } }
  return <Form {...form}>
    <form onSubmit={form.handleSubmit(onSubmit)} className="grid max-w-sm gap-4">
      <FormField control={form.control} name="usuario" render={({ field }) => (
        <FormItem><FormLabel>Usuario</FormLabel><FormControl><Input placeholder="lmartin" {...field} /></FormControl>
          <FormDescription>Es tu nombre de acceso al portal.</FormDescription><FormMessage /></FormItem>)} />
      <Button type="submit" className="justify-self-start">Guardar</Button>
    </form>
  </Form>
}
export default function Preview() {
  return (<Demo />)
}
