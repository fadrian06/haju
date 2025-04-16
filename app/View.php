<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Jenssegers\Blade\Blade;

final readonly class View {
  private static function getInstance(): Blade {
    static $blade = null;

    if (!$blade) {
      $container = Container::getInstance();
      $container->bind(Application::class, Container::class);
      $container->alias('view', Factory::class);

      $blade = new Blade(
        __DIR__ . '/../resources/views',
        __DIR__ . '/../storage/cache',
        $container,
      );
    }

    return $blade;
  }

  public static function render(string $view, array $data = []): void {
    echo self::getInstance()->render($view, $data);
  }
}
