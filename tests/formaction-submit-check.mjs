// Quick view must leave a submit button with its own formaction (Swift's Buy
// Now) to the browser, and keep sending the plain Add to cart by AJAX.
// Run: node tests/formaction-submit-check.mjs
import { readFileSync } from 'node:fs';
import vm from 'node:vm';

const listeners = {};
const el = (extra = {}) => ({
  addEventListener: (type, fn) => { (listeners[type] ??= []).push(fn); },
  querySelector: () => null,
  setAttribute() {}, removeAttribute() {},
  classList: { contains: () => false, add() {}, remove() {} },
  ...extra,
});
let ready;
class HTMLFormElement {}
const ctx = {
  window: { peekQuickView: {}, matchMedia: () => ({ matches: false }) },
  document: { addEventListener: (t, fn) => { if (t === 'DOMContentLoaded') ready = fn; }, querySelector: () => el(), body: el() },
  HTMLFormElement,
  FormData: class { has() { return true; } append() {} },
  fetch: () => new Promise(() => {}),
  CustomEvent: class {},
  console,
};
vm.runInNewContext(readFileSync(new URL('../assets/js/quick-view.js', import.meta.url), 'utf8'), ctx);
ready();

const submit = (submitter) => {
  const form = Object.assign(new HTMLFormElement(), el({
    classList: { contains: (c) => c === 'cart' },
    closest: () => el(),
  }));
  let prevented = false;
  listeners.submit.forEach((fn) => fn({ target: form, submitter, defaultPrevented: false, preventDefault: () => { prevented = true; } }));
  return prevented;
};
const button = (attrs) => ({ name: '', value: '', disabled: false, classList: { add() {} }, hasAttribute: (a) => a in attrs });

let fail = 0;
const check = (ok, msg) => { console.log((ok ? 'ok   ' : 'FAIL ') + msg); if (!ok) fail++; };
check(!submit(button({ formaction: 1 })), 'Buy Now with formaction is left to the browser');
check(submit(button({})), 'plain Add to cart is still sent by AJAX');
check(submit(null), 'a submit without submitter is still sent by AJAX');
console.log(fail ? 'FAIL' : 'OK: formaction submits bypass the quick-view AJAX handler');
process.exit(fail ? 1 : 0);
