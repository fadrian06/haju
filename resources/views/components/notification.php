<?php



use HAJU\Enums\NotificationType;

$text = isset($type) ? strval($type) : throw new Error('Text not set');
$type = is_string($type) ? NotificationType::from(strval($type)) : throw new Error('Type not set');

?>

<div class="alert alert-<?= $type->getBootstrapColor() ?> alert-dismissible fade show">
  <?= $text ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
