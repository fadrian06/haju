import "@fontsource/poppins/latin.css";
import "@fontsource/rajdhani/latin.css";
import "bootstrap/dist/css/bootstrap.min.css";
import "sweetalert2/dist/sweetalert2.min.css";
import "@fortawesome/fontawesome-free/css/all.min.css";
import "metismenujs/style";
import "pure-css-loader/dist/loader-default.css";
import "./customizations.css";
// import "./theme/index.css";

import "alpinejs/dist/cdn.min";
import { Modal } from "bootstrap";
import { MetisMenu } from "metismenujs";
import Swal from "sweetalert2";
import "./setups/bootstrap-toasts";
import "./setups/bootstrap-tooltips";

globalThis.customSwal = Swal.mixin({
  showCloseButton: true,
  customClass: {
    confirmButton: "btn btn-primary mx-2",
    denyButton: "btn btn-danger mx-2",
  },
  buttonsStyling: false,
});

if (location.href.endsWith("#registrar")) {
  new Modal("#registrar").show();
}

new MetisMenu("#sidebar-navigation");
