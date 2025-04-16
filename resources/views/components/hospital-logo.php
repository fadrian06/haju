<?php

declare(strict_types=1);

use Leaf\Http\Session;

?>

<img
  loading="eager"
  src="./resources/images/logo@<?= Session::get('theme', 'light') ?>.png"
  :src="`./resources/images/logo@${theme}.png`"
  data-bs-toggle="tooltip"
  height="50"
  title='Hospital "Antonio José Uzcátegui"'
  alt='Logo de HAJU (Hospital "Antonio José Uzcátegui")' />
