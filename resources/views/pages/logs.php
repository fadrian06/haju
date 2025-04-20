<?php

/**
 * @var string[] $logs
 */

?>

<ul class="list-group mb-4">
  <?php foreach ($logs as $log) : ?>
    <li class="list-group-item"><?= $log ?></li>
  <?php endforeach ?>
  <?php if (!$logs) : ?>
    <li class="list-group-item">No hay logs</li>
  <?php endif ?>
</ul>
<a href="./logs/vaciar" class="btn btn-primary btn-lg">Vaciar</a>
