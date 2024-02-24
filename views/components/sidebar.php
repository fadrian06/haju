<nav class="sidebar">
  <div class="logo p-4 m-0 d-flex justify-content-between align-items-center">
    <img src="<?= asset('img/logo.png') ?>" height="59" />
    <div class="sidebar_close_icon d-flex align-items-center d-lg-none">
      <i class="ti-close"></i>
    </div>
  </div>
  <menu class="m-0 p-0" id="sidebar_menu">
    <li class="side_menu_title">
      <span>Panel de Administración</span>
    </li>
    <li class="mm-active">
      <a href="<?= route('/') ?>">
        <img src="<?= asset('img/menu-icon/1.svg') ?>" />
        <span>Inicio</span>
      </a>
    </li>
  </menu>
</nav>
