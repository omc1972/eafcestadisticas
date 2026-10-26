# Chart

Gráficos de Recharts con los colores, la leyenda y el tooltip de Hacce.

## Cuándo usarlo

- Series en orden: `chart-1` (azul de marca), `chart-2` (ámbar), `chart-3` (gris), `chart-4`, `chart-5`.
- Títulos y unidades fuera del gráfico, en una `Card`. Pocas series (1–3).

## Qué tienes que pasarle

`ChartContainer` con `config` (`{ clave: { label, color } }`) y un gráfico de `Recharts` (exportado como `HacceUI.Recharts`); `ChartTooltip` + `ChartTooltipContent`, `ChartLegend` + `ChartLegendContent`. Pinta cada serie con `var(--color-<clave>)`.

## Ejemplo

```tsx
const config = { presencial: { label: "Presencial", color: "var(--chart-1)" } }
<ChartContainer config={config} className="h-60 w-full">
  <BarChart data={datos}>
    <XAxis dataKey="mes" />
    <ChartTooltip content={<ChartTooltipContent />} />
    <Bar dataKey="presencial" fill="var(--color-presencial)" radius={3} />
  </BarChart>
</ChartContainer>
```
