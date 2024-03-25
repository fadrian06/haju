<?php

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
