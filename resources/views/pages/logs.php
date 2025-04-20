<?php

declare(strict_types=1);

$logs ??= ['No hay logs'];

?>

<ul class="list-group mb-4">
  <?php foreach ($logs as $log): ?>
    <li class="list-group-item"><?= $log ?></li>
  <?php endforeach ?>
</ul>
<a href="./logs/vaciar" class="btn btn-primary btn-lg">Vaciar</a>
