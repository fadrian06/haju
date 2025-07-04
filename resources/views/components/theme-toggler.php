<?php

?>

<li class="dropdown" data-bs-toggle="tooltip" title="Cambiar tema" data-bs-placement="left">
  <button
    type="button"
    class="btn btn-link text-decoration-none p-2 dropdown-toggle d-flex align-items-center"
    data-bs-toggle="dropdown"
    data-bs-display="static">
    <span class="visually-hidden">Cambiar tema</span>
    <i class="fa" :class="theme === 'light' ? 'fa-sun' : 'fa-moon'"></i>
  </button>
  <menu class="dropdown-menu dropdown-menu-end py-0">
    <button
      type="button"
      class="dropdown-item d-flex align-items-center gap-1 p-3"
      :class="`${theme === 'light' && 'active'}`"
      @click="setTheme('light')">
      <i class="fa fa-sun"></i>
      Claro
    </button>
    <button
      type="button"
      class="dropdown-item d-flex align-items-center gap-1 p-3"
      :class="`${theme === 'dark' && 'active'}`"
      @click="setTheme('dark')">
      <i class="fa fa-moon"></i>
      Oscuro
    </button>
  </menu>
</li>
