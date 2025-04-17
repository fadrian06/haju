<?php

declare(strict_types=1);

use flight\net\Request;
use flight\template\View;
use Illuminate\Container\Container;
use Leaf\Http\Session;
use Psr\Container\ContainerInterface;

$laravelContainer = Container::getInstance();

$laravelContainer->singleton(
  ContainerInterface::class,
  $laravelContainer::class
);

$laravelContainer->singleton(Session::class);
$laravelContainer->singleton(View::class, static fn(): View => Flight::view());

$laravelContainer->singleton(
  Request::class,
  static fn(): Request => Flight::request()
);
