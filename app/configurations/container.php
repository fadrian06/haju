<?php

declare(strict_types=1);

use flight\net\Request;
use flight\template\View;
use Illuminate\Container\Container;
use Leaf\Http\Session;
use Psr\Container\ContainerInterface;

Container::getInstance()->singleton(
  ContainerInterface::class,
  Container::class
);

Container::getInstance()->singleton(Session::class);

Container::getInstance()->singleton(
  View::class,
  static fn(): View => Flight::view()
);

Container::getInstance()->singleton(
  Request::class,
  static fn(): Request => Flight::request()
);
