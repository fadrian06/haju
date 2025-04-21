import "bootstrap/dist/css/bootstrap.min.css";
import { Tooltip } from "bootstrap";

for (const element of document.querySelectorAll('[data-bs-toggle="tooltip"]')) {
  new Tooltip(element);
}
