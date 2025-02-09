import "@fontsource/poppins/400.css";
import "@fontsource/rajdhani/700.css";
import "bootstrap/dist/css/bootstrap.min.css";
import "./customizations.css";
import "@fortawesome/fontawesome-free/css/all.min.css";
import "sweetalert2/dist/sweetalert2.min.css";
import "alpinejs/dist/cdn.min.js";
import { Tooltip } from "bootstrap";
import Swal from "sweetalert2";

for (const element of document.querySelectorAll('[data-bs-toggle="tooltip"]')) {
  new Tooltip(element);
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
});
