<?php

declare(strict_types=1);

namespace HAJU;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Events\Dispatcher as EventsDispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\ViewFinderInterface;
use Jenssegers\Blade\Blade;
use Jenssegers\Blade\Container;
use Psr\Container\ContainerInterface;

final readonly class View {
  public static function render(string $view, array $data = []): void {
    echo self::getInstance()->render($view, $data);
  }

  private static function getInstance(): Blade {
    static $blade = null;

    if (!$blade) {
      $container = new class extends Container {
        public function getNamespace(): string {
          return __NAMESPACE__;
        }
      };

      $container->singleton(
        ViewFinderInterface::class,
        static fn(): FileViewFinder => new FileViewFinder(new Filesystem, [
          __DIR__ . '/../resources/views',
        ])
      );

      $container->singleton(Dispatcher::class, EventsDispatcher::class);
      $container->singleton(Factory::class, ViewFactory::class);

      $container->singleton(
        Application::class,
        static fn(): ContainerInterface => $container
      );

      Container::setInstance($container);

      $blade = new Blade(
        __DIR__ . '/../resources/views',
        __DIR__ . '/../storage/cache',
        $container,
      );
    }

    return $blade;
  }
}
