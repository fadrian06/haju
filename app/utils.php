<?php

declare(strict_types=1);

use flight\Container;

function container(): Container {
  static $container = null;

  if ($container === null) {
    $container = new Container;
  }

  return $container;
}

/** @deprecated */
function render(string $viewPath, array $params = []): void {
  Flight::render($viewPath, $params);
}

function renderPage(
  string $page,
  string $title,
  array $params = [],
  string $layout = 'base'
): void {
  $params['title'] = $title;

  Flight::render("pages/{$page}", $params, 'content');
  Flight::render("layouts/{$layout}", $params);
}

/** @deprecated */
function renderComponent(string $componentPath, array $params = []): void {
  Flight::render("components/{$componentPath}", $params);
}

function isActive(string ...$urls): bool {
  foreach ($urls as $url) {
    if ($url === '/') {
      if (Flight::request()->url === $url) {
        return true;
      }
    } elseif (str_starts_with(Flight::request()->url, $url)) {
      return true;
    }
  }

  return false;
}
