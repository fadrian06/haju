<?php

declare(strict_types=1);

$layouts = join(PHP_EOL, array_map(
  static function (string $layout): string {
    $layoutName = basename($layout, '.php');

    return '  case ' . strtoupper($layoutName) . " = '$layoutName';";
  },
  glob(dirname(__DIR__) . '/resources/views/layouts/*.php'),
));

file_put_contents(dirname(__DIR__) . '/app/Enums/Layout.php', <<<php
<?php

declare(strict_types=1);

namespace HAJU\Enums;

enum Layout: string
{
$layouts
}

php);

echo "\n✅ Updated Layout enum with new cases.\n\n";
