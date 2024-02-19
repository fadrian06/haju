<?php

function asset(string $filePath): string {
  return App::request()->base . "/assets/$filePath";
}

function route(string $name): string {
  return App::request()->base . App::getUrl($name);
}
