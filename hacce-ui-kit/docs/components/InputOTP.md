# InputOTP

Código de un solo uso por casillas (verificación en dos pasos).

## Cuándo usarlo

- Solo para códigos de verificación de 4–8 cifras.
- Explica debajo de dónde sale el código y cuánto dura.

## Qué tienes que pasarle

`maxLength`, `value` / `onChange`, `pattern`; hijos `InputOTPGroup` con `InputOTPSlot index={n}` y `InputOTPSeparator`.

## Ejemplo

```tsx
<InputOTP maxLength={6}>
  <InputOTPGroup><InputOTPSlot index={0} /><InputOTPSlot index={1} /><InputOTPSlot index={2} /></InputOTPGroup>
  <InputOTPSeparator />
  <InputOTPGroup><InputOTPSlot index={3} /><InputOTPSlot index={4} /><InputOTPSlot index={5} /></InputOTPGroup>
</InputOTP>
```
