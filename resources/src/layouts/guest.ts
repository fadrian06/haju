import 'alpinejs/dist/cdn.min'
import 'bootstrap/js/dist/carousel'
import 'bootstrap/js/dist/dropdown'
import 'bootstrap/js/dist/modal'
import Tooltip from 'bootstrap/js/dist/tooltip'
import Swal from 'sweetalert2'
import '../styles/customizations.scss'

const tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]')

for (const element of Array.from(tooltipElements)) {
  new Tooltip(element)
}

globalThis.customSwal = Swal.mixin({
  showCloseButton: true,
  customClass: {
    confirmButton: 'btn btn-primary mx-2',
    denyButton: 'btn btn-danger mx-2',
    popup: 'bg-white border',
    title: 'bg-white',
  },
  buttonsStyling: false,
  keydownListenerCapture: true,
})
