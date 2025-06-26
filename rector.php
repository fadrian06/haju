<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
  ->withAttributesSets()
  ->withAutoloadPaths([])
  ->withBootstrapFiles([])
  ->withCache()
  ->withComposerBased()
  // ->withConfiguredRule()
  ->withDowngradeSets(php82: true)
  ->withFluentCallNewLine()
  ->withImportNames(removeUnusedImports: true)
  ->withIndent(' ', 2)
  ->withParallel()
  ->withPaths([
    __DIR__ . '/app',
    __DIR__ . '/tests',
    __DIR__ . '/resources/views',
  ])
  ->withPhpSets(php82: true)
  ->withPreparedSets(
    deadCode: false,
    codeQuality: false,
    codingStyle: false,
    typeDeclarations: false,
    privatization: false,
    naming: false,
    instanceOf: false,
    earlyReturn: false,
    strictBooleans: false,
    rectorPreset: false,
    phpunitCodeQuality: false,
  )
  ->withRealPathReporting()
  ->withRootFiles()
  ->withSkip([])
  ->withSkipPath(__DIR__ . '/vendor')
  ->withSkipPath(__DIR__ . '/node_modules')
  ->withSkipPath(__DIR__ . '/.git')
;
