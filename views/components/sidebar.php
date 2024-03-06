<aside class="sidebar">
  <header class="logo m-0 d-flex align-items-center justify-content-between">
    <img src="<?= asset('img/logo.png') ?>" height="59" />
    <div class="sidebar_close_icon d-flex align-items-center d-lg-none">
      <i class="ti-close"></i>
    </div>
  </header>
  <menu class="m-0 p-0" id="sidebar_menu">
    <li class="side_menu_title">
      <span>Panel de Administración</span>
    </li>
    <li class="<?= isActive('/') ? 'mm-active' : '' ?>">
      <a href="<?= route('/') ?>">
        <img src="<?= asset('img/icons/house.svg') ?>" />
        <span>Inicio</span>
      </a>
    </li>
    <li class="<?= isActive('/departamentos') ? 'mm-active' : '' ?>">
      <a href="<?= route('/departamentos') ?>">
        <img src="<?= asset('img/icons/hospital.svg') ?>" />
        <span>Departamentos</span>
      </a>
    </li>
  </menu>
</aside>
