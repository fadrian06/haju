<?php

declare(strict_types=1);

?>

<aside
  id="sidebar"
  class="offcanvas offcanvas-start px-3 overflow-y-scroll"
  :class="`text-bg-${theme}`">
  <header class="offcanvas-header gap-3">
    <picture>
      <?php Flight::render('components/hospital-logo', ['class' => 'img-fluid']) ?>
    </picture>
    <button
      class="btn fa fa-close fa-2x d-lg-none"
      @mouseenter="$el.classList.add('fa-beat')"
      @mouseleave="$el.classList.remove('fa-beat')"
      data-bs-dismiss="offcanvas">
    </button>
  </header>
  <?php Flight::render('components/sidebar-navigation') ?>
</aside>
