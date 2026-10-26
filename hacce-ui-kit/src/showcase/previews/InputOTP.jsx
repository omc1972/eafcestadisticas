// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { InputOTP, InputOTPGroup, InputOTPSlot, InputOTPSeparator } = HacceUI
export default function Preview() {
  return (<InputOTP maxLength={6} defaultValue="482">
  <InputOTPGroup><InputOTPSlot index={0} /><InputOTPSlot index={1} /><InputOTPSlot index={2} /></InputOTPGroup>
  <InputOTPSeparator />
  <InputOTPGroup><InputOTPSlot index={3} /><InputOTPSlot index={4} /><InputOTPSlot index={5} /></InputOTPGroup>
</InputOTP>)
}
