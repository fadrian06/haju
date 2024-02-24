<?php

use App\Models\GenrePrefix;
use App\Models\User;

$showRegister = App::userRepository()->getAll() === [];

App::route('/', function (): void {
  session_start();

  if (!key_exists('userId', $_SESSION)) {
    App::redirect('/ingresar');

    return;
  }

  $user = App::userRepository()->getById((int) $_SESSION['userId']);

  if (!$user) {
    App::redirect('/salir');

    return;
  }

  App::render('pages/home', [], 'content');
  App::render('layouts/main', ['title' => 'Inicio', ...compact('user')]);
});

App::route('/salir', function (): void {
  session_start();
  session_destroy();

  App::redirect('/ingresar');
});

App::route('GET /ingresar', function () use ($showRegister): void {
  session_start();

  if (key_exists('userId', $_SESSION)) {
    App::redirect('/');

    return;
  }

  $error = $_SESSION['error'] ?? null;
  $message = $_SESSION['message'] ?? null;

  unset($_SESSION['error']);
  unset($_SESSION['message']);

  App::render('pages/login', compact('showRegister', 'error', 'message'), 'content');
  App::render('layouts/base', ['title' => 'Ingreso']);
});

App::route('POST /ingresar', function (): void {
  $user = App::userRepository()->getByIdCard((int) App::request()->data['id_card']);

  session_start();

  if (!$user?->checkPassword(App::request()->data['password'])) {
    $_SESSION = ['error' => '❌ Cédula o contraseña incorrecta'];

    App::redirect('/ingresar');

    return;
  }

  $_SESSION = ['userId' => $user->getId()];

  App::redirect('/');
});

App::route('GET /registrate', function () use ($showRegister): void {
  App::render('pages/register', compact('showRegister'), 'content');
  App::render('layouts/base', ['title' => 'Regístrate']);
});

App::route('POST /registrate', function (): void {
  $data = App::request()->data;

  $user = new User(
    $data['first_name'],
    $data['last_name'],
    $data['speciality'],
    GenrePrefix::tryFrom($data['prefix'] ?? ''),
    (int) $data['id_card'],
    $data['password'],
    $data['avatar']
  );

  App::userRepository()->save($user);
  session_start();

  $_SESSION['message'] = '✔ Usuario registrado exitósamente';

  App::redirect('/ingresar');
});

App::route('GET /recuperar', function () use ($showRegister): void {
  session_start();

  $error = $_SESSION['error'] ?? null;

  unset($_SESSION['error']);

  App::render('pages/forgot-pass', compact('showRegister', 'error'), 'content');
  App::render('layouts/base', ['title' => 'Recuperar contraseña (1/2)']);
});

App::route('POST /recuperar', function () use ($showRegister): void {
  session_start();

  if (App::request()->data['id_card']) {
    $user = App::userRepository()->getByIdCard((int) App::request()->data['id_card']);

    if ($user) {
      App::render('pages/change-pass', compact('showRegister', 'user'), 'content');
      App::render('layouts/base', ['title' => 'Recuperar contraseña (2/2)']);

      return;
    }

    $_SESSION['error'] = '❌ Cédula incorrecta';

    App::redirect('/recuperar');

    return;
  }

  $user = App::userRepository()->getById(App::request()->data['id'])
    ->setPassword(App::request()->data['password']);

  App::userRepository()->save($user);

  $_SESSION['message'] = '✔ Contraseña actualizada exitósamente';

  App::redirect('/ingresar');
});

App::route('/perfil', function (): void {
});

App::route('/configuracion', function (): void {
});

App::route('/notificaciones', function (): void {
});
