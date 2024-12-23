<?php

/**
 * @var 'message'|'info'|'error' $type
 * @var string $text
 */

if (!defined('TYPES')) {
  define('TYPES', [
    'message' => 'success',
    'info' => 'info',
    'error' => 'danger'
  ]);
}

?>

<div class="alert alert-<?= TYPES[$type] ?> alert-dismissible fade show">
  <?= $text ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
