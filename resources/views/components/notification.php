<?php

declare(strict_types=1);

if (!enum_exists('TYPES')) {
  enum Types: string {
    case message = 'success';
    case info = 'info';
    case error = 'danger';
  }
}

$text = isset($text) ? strval($text) : throw new Error('text is required');

$type = isset($type) && is_string($type)
  ? Types::from($type)
  : throw new Error('type is required');

?>

<div class="alert alert-<?= $type->value ?> alert-dismissible fade show">
  <?= $text ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
