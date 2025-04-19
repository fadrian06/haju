import "alpinejs/dist/cdn.min";
import "bootstrap/js/dist/carousel";
import "bootstrap/js/dist/dropdown";
import "bootstrap/js/dist/modal";
import Tooltip from "bootstrap/js/dist/tooltip";
import Swal from "sweetalert2";
import "../styles/customizations.scss";
import { Toast } from "bootstrap";

for (const element of document.querySelectorAll('[data-bs-toggle="tooltip"]')) {
  new Tooltip(element);
}

for (const element of document.querySelectorAll(".toast")) {
  new Toast(element).show();
}

globalThis.customSwal = Swal.mixin({
  showCloseButton: true,
  customClass: {
    confirmButton: "btn btn-primary mx-2",
    denyButton: "btn btn-danger mx-2",
    popup: "bg-white border",
    title: "bg-white",
  },
  buttonsStyling: false,
  keydownListenerCapture: true,
});
