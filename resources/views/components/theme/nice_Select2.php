<?php



static $isFirstRender = true;

?>

<select class="nice_Select2">
  <option value="">--Please choose an option--</option>
  <option value="dog">Dog</option>
  <option value="cat">Cat</option>
  <option value="hamster">Hamster</option>
  <option value="parrot">Parrot</option>
  <option value="spider">Spider</option>
  <option value="goldfish">Goldfish</option>
</select>

<?php if ($isFirstRender) : ?>
  <script src="./node_modules/jquery-nice-select/js/jquery.js"></script>
  <script src="./node_modules/jquery-nice-select/js/jquery.nice-select.min.js"></script>

  <script>
    $(document).ready(() => $('.nice_Select2').niceSelect());
  </script>

  <?php $isFirstRender = false ?>
<?php endif ?>
