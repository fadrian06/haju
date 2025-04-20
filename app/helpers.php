<?php

declare(strict_types=1);

use flight\Container;
use flight\template\View;

function renderPage(
  string $page,
  string $title,
  array $params = [],
  string $layout = 'guest'
): void {
  $params['title'] = $title;
  $view = Container::getInstance()->get(View::class);

  $params['content'] = $view->fetch("pages/{$page}", $params);
  $view->render("layouts/{$layout}", $params);
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
