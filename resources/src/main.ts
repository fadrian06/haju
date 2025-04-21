import "@fontsource/poppins/latin.css";
import "@fontsource/rajdhani/latin.css";
import "bootstrap/dist/css/bootstrap.min.css";
import "sweetalert2/dist/sweetalert2.min.css";
import "@fortawesome/fontawesome-free/css/all.min.css";
import "metismenujs/style";
import "./customizations.css";
// import "./theme/index.css";

import "alpinejs/dist/cdn.min";
import "../../node_modules/metismenujs/dist/metismenujs.min";
import "sweetalert2/dist/sweetalert2.all.min";
import { Modal, Toast, Tooltip } from "bootstrap";
import { MetisMenu } from "metismenujs";
import Swal from "sweetalert2";

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

for (const element of document.querySelectorAll('[data-bs-toggle="tooltip"]')) {
  new Tooltip(element);
}

for (const element of document.querySelectorAll(".toast")) {
  const toast = new Toast(element);

  toast.show();
}

if (location.href.endsWith("#registrar")) {
  new Modal("#registrar").show();
}

new MetisMenu("#sidebar_menu");
