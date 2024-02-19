<?php

$showRegister = true;

App::route('/ingresar', function () use ($showRegister): void {
  App::render('pages/login', compact('showRegister'), 'content');
  App::render('layouts/base', ['title' => 'Ingreso']);
});

App::route('/registrate', function () use ($showRegister): void {
  App::render('pages/register', compact('showRegister'), 'content');
  App::render('layouts/base', ['title' => 'Regístrate']);
});

App::route('/recuperar', function () use ($showRegister): void {
  App::render('pages/forgot-pass', compact('showRegister'), 'content');
  App::render('layouts/base', ['title' => 'Recuperar contraseña']);
});
