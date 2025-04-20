<?php

declare(strict_types=1);

use Leaf\Http\Session;

?>

<img
  loading="eager"
  src="./assets/img/logo@<?= Session::get('theme', 'light') ?>.png"
  :src="`./assets/img/logo@${theme}.png`"
  data-bs-toggle="tooltip"
  height="50"
  title='Hospital "Antonio José Uzcátegui"'
  alt='Logo de HAJU (Hospital "Antonio José Uzcátegui")' />
