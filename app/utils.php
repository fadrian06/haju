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

function isActive(string $url): bool {
  return $url === App::request()->url;
}
