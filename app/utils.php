<?php

function asset(string $filePath): string {
  return App::get('root') . "/assets/$filePath";
}

function route(string $name): string {
  return App::get('root') . App::getUrl($name);
}