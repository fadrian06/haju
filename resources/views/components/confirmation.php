<?php

declare(strict_types=1);

$show = isset($show) ? boolval($show) : true;
$id = isset($id) ? strval($id) : throw new Error('id is required');
$action = isset($action) ? strval($action) : throw new Error('action is required');
$title = isset($title) ? strval($title) : throw new Error('title is required');
$confirmText ??= 'Confirmar';
$denyText = isset($denyText) && $denyText === false ? false : 'Cancelar';

?>

<div class="modal fade <?= $denyText === false ? 'pe-none' : '' ?>" id="<?= $id ?>">
  <div class="modal-dialog">
    <form action="<?= $action ?>" class="modal-content">
      <header class="modal-header">
        <h3 class="modal-title fs-5">
          <?= $title ?>
        </h3>
        <?php if ($denyText !== false) : ?>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        <?php endif ?>
      </header>
      <footer class="modal-footer">
        <button class="btn btn-danger"><?= $confirmText ?></button>
        <?php if ($denyText !== false) : ?>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <?= $denyText ?>
          </button>
        <?php endif ?>
        <button hidden type="button" id="<?= $id ?>-toggler" data-bs-toggle="modal" data-bs-target="#<?= $id ?>"></button>
      </footer>
    </form>
  </div>
</div>
<?php if ($show) : ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.getElementById('<?= $id ?>-toggler').click()
    })
  </script>
<?php endif ?>
