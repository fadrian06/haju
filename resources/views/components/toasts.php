<?php

declare(strict_types=1);

$errors ??= [];
$success ??= [];

?>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <?php foreach ($errors as $error) : ?>
    <div class="toast">
      <div class="toast-header text-danger">
        <i class="fa fa-times-circle me-2"></i>
        <strong class="me-auto"><?= $error ?></strong>
        <button class="btn-close" data-bs-dismiss="toast"></button>
      </div>
    </div>
  <?php endforeach ?>
  <?php if ($success) : ?>
    <div class="toast">
      <div class="toast-header text-success">
        <i class="fa fa-check-circle me-2"></i>
        <strong class="me-auto"><?= $success ?></strong>
        <button class="btn-close" data-bs-dismiss="toast"></button>
      </div>
    </div>
  <?php endif ?>
</div>
