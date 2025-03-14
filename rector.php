<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
  ->withPaths([
    __DIR__ . '/app',
    __DIR__ . '/tests',
  ])
  ->withPhpSets(php82: true)
  ->withPreparedSets(
    deadCode: true,
    codeQuality: true,
    codingStyle: true,
    typeDeclarations: true,
    privatization: true,
    naming: true,
    instanceOf: true,
    earlyReturn: true,
    strictBooleans: true,
    rectorPreset: true,
    phpunitCodeQuality: true,
  )
  ->withRootFiles();
