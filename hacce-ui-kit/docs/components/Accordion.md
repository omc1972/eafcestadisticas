# Accordion

Secciones que se despliegan una a una.

## Cuándo usarlo

- Preguntas frecuentes y ajustes poco usados. No escondas ahí información que todo el mundo necesita.

## Qué tienes que pasarle

`Accordion` (`type`: `single` | `multiple`, `collapsible`, `defaultValue`), `AccordionItem value`, `AccordionTrigger` y `AccordionContent`.

## Ejemplo

```tsx
<Accordion type="single" collapsible>
  <AccordionItem value="a"><AccordionTrigger>¿Puedo teletrabajar?</AccordionTrigger><AccordionContent>Sí, hasta tres días por semana.</AccordionContent></AccordionItem>
</Accordion>
```
