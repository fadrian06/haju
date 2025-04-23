<?php

declare(strict_types=1);

use Leaf\Http\Session;

$class ??= '';

?>

<img
  loading="eager"
  src="./assets/img/logo@<?= Session::get('theme', 'light') ?>.png"
  :src="`./assets/img/logo@${theme}.png`"
  data-bs-toggle="tooltip"
  data-bs-placement="bottom"
  height="50"
  title='Hospital "Antonio José Uzcátegui"'
  alt='Logo de HAJU (Hospital "Antonio José Uzcátegui")'
  class="<?= $class ?>" />
