<?php

function render(string $componentPath, array $params = []): void {
  App::render($componentPath, $params);
}

function isActive(string ...$urls): bool {
  foreach ($urls as $url) {
    if ($url === '/') {
      if (App::request()->url === $url) {
        return true;
      }
    } elseif (str_starts_with(App::request()->url, $url)) {
      return true;
    }
  }

  return false;
}
