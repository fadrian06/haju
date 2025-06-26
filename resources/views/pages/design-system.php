<?php



use Leaf\Http\Session;

?>

<!doctype html>
<html
  lang="es"
  x-data="{
    theme: `<?= Session::get('theme', 'light') ?>`,

    setTheme(theme = 'light') {
      this.theme = theme;
      fetch(`./api/preferencias/tema/${theme}`);
    },
  }"
  data-bs-theme="<?= Session::get('theme', 'light') ?>"
  :data-bs-theme="theme">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title>Design System</title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="stylesheet" href="./resources/dist/design-system.css" />
</head>

<body class="d-flex flex-column gap-5 pb-5">
  <?php Flight::render('components/headers/public') ?>

  <section class="container">
    <h2>.CRM_dropdown</h2>

    <div class="dropdown d-inline-block">
      <button
        class="btn btn-secondary dropdown-toggle"
        type="button"
        data-bs-toggle="dropdown">
        Dropdown button
      </button>
      <ul class="dropdown-menu">
        <li>
          <a class="dropdown-item">Action</a>
        </li>
        <li>
          <a class="dropdown-item">Another action</a>
        </li>
        <li>
          <a class="dropdown-item">Something else here</a>
        </li>
      </ul>
    </div>

    <?php Flight::render('components/theme/CRM_dropdown') ?>
  </section>

  <div class="container border_bottom_1px">
    <h2>.border_bottom_1px</h2>
  </div>

  <section class="container">
    <h2>input:focus</h2>

    <label for="name">Name (4 to 8 characters):</label>

    <input id="name" />
  </section>

  <section class="container">
    <h2>.custom-file-input</h2>

    <input class="custom-file-input" type="file" id="avatar" />
    <label class="custom-file-label" for="avatar">Choose a profile picture:</label>
  </section>

  <section class="container">
    <h2>.form-control</h2>

    <div class="mb-3">
      <label for="exampleFormControlInput1" class="form-label">Email address</label>
      <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
    </div>
    <div class="mb-3">
      <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
      <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
    </div>
  </section>

  <section class="container section_padding border border-primary">
    <h2>.section_padding</h2>
  </section>

  <section class="container padding_top border border-primary">
    <h2>.padding_top</h2>
  </section>

  <section class="container padding_bottom border border-primary">
    <h2>.padding_bottom</h2>
  </section>

  <section class="container">
    <h2>&lt;a&gt;</h2>
    <p>You can reach Michael at:</p>

    <ul>
      <li>
        <a href="https://example.com">Website</a>
      </li>
      <li>
        <a href="mailto:m.bluth@example.com">Email</a>
      </li>
      <li>
        <a href="tel:+123456789">Phone</a>
      </li>
    </ul>
  </section>

  <section class="container">
    <h1 class="display-1">Headings</h1>

    <h1>Beetles</h1>
    <h2>External morphology</h2>
    <h3>Head</h3>
    <h4>Mouthparts</h4>
    <h5>Thorax</h5>
    <h6>Prothorax</h6>
  </section>

  <section class="container">
    <h2>&lt;li&gt;</h2>

    <p>Apollo astronauts:</p>

    <ul>
      <li>Neil Armstrong</li>
      <li>Alan Bean</li>
      <li>Peter Conrad</li>
      <li>Edgar Mitchell</li>
      <li>Alan Shepard</li>
    </ul>
  </section>

  <section class="container">
    <h2>&lt;ul&gt;</h2>

    <ul>
      <li>Milk</li>
      <li>
        Cheese
        <ul>
          <li>Blue cheese</li>
          <li>Feta</li>
        </ul>
      </li>
    </ul>
  </section>

  <section class="container">
    <h2>&lt;ol&gt;</h2>

    <ol>
      <li>Mix flour, baking powder, sugar, and salt.</li>
      <li>In another bowl, mix eggs, milk, and oil.</li>
      <li>Stir both mixtures together.</li>
      <li>Fill muffin tray 3/4 full.</li>
      <li>Bake for 20 minutes.</li>
    </ol>
  </section>

  <section class="container">
    <h2>&lt;p&gt;</h2>

    <p>
      Geckos are a group of usually small, usually nocturnal lizards. They are
      found on every continent except Antarctica.
    </p>

    <p>
      Some species live in houses where they hunt insects attracted by
      artificial light.
    </p>
  </section>

  <section class="container section_bg">
    <h2>.section_bg</h2>
  </section>

  <section class="container">
    <h2>.cs_checkbox</h2>

    <?php Flight::render('components/theme/cs_checkbox') ?>
  </section>

  <section class="container">
    <header class="section_tittle">
      <h2>.section_tittle</h2>
      <p>
        Geckos are a group of usually small, usually nocturnal lizards. They
        are found on every continent except Antarctica.
      </p>
      <p>
        Some species live in houses where they hunt insects attracted by
        artificial light.
      </p>
    </header>
  </section>

  <section class="container">
    <h2 class="border_1px">.border_1px</h2>
  </section>

  <section class="container">
    <h2>.nice_Select</h2>

    <div class="d-flex align-items-start gap-5">
      <select>
        <option value="">--Please choose an option--</option>
        <option value="dog">Dog</option>
        <option value="cat">Cat</option>
        <option value="hamster">Hamster</option>
        <option value="parrot">Parrot</option>
        <option value="spider">Spider</option>
        <option value="goldfish">Goldfish</option>
      </select>

      <?php Flight::render('components/theme/nice_Select2') ?>
    </div>
  </section>

  <section class="container white_box">
    <h3>.white_box <span>.white_box h3 span</span></h3>
  </section>

  <section class="container">
    <h2>.cu_dropdown</h2>

    <?php Flight::render('components/theme/cu_dropdown') ?>
  </section>

  <section class="container">
    <h2>.switch</h2>

    <?php Flight::render('components/theme/switch') ?>
  </section>

  <section class="container">
    <h2>.menu_bropdown</h2>

    <?php Flight::render('components/theme/menu_bropdown') ?>
  </section>

  <script src="./resources/dist/design-system.js"></script>
</body>

</html>
