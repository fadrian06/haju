import "@fontsource/poppins/400.css";
import "@fontsource/rajdhani/700.css";
import "@fortawesome/fontawesome-free/css/all.min.css";
import "bootstrap/dist/css/bootstrap.min.css";
import "sweetalert2/dist/sweetalert2.min.css";
import "./customizations.css";

import "alpinejs/dist/cdn.min";
import Swal from "sweetalert2";
import "./setups/bootstrap-tooltips";
import "./setups/bootstrap-toasts";
import "./setups/bootstrap-popovers";

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
