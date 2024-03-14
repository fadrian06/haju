<?php

function asset(string $filePath): string {
  return App::get('root') . "/assets/$filePath";
}

function route(string $name, array $params = []): string {
  return App::get('root') . App::getUrl($name, $params);
}

function render(string $componentPath, array $params = []): void {
  App::render($componentPath, $params);
}

function isActive(string ...$urls): bool {
  foreach ($urls as $url) {
    if ($url === App::request()->url) {
      return true;
    }
  }

  return false;
}
