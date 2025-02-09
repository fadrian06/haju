<?php

function render(string $viewPath, array $params = []): void {
  App::render($viewPath, $params);
}

function renderComponent(string $componentPath, array $params = []): void {
  render("components/$componentPath", $params);
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
