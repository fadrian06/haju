<?php

/**
 * @var ?bool $show
 * @var string $id
 * @var string $action
 * @var string $title
 * @var ?string $confirmText
 * @var null|string|false $denyText
 */

$show ??= true;
$confirmText ??= 'Confirmar';
$denyText ??= 'Cancelar';

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
