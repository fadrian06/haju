import { Popover } from "bootstrap";

for (const element of document.querySelectorAll('[data-bs-toggle="popover"]')) {
  new Popover(element, {
    html: true,
    sanitize: false,
  });
}
