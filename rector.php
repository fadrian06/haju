<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\CompactToVariablesRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\If_\NullableCompareToNullRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector;
use Rector\Naming\Rector\Class_\RenamePropertyToMatchTypeRector;
use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use Rector\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector;
use Rector\Strict\Rector\If_\BooleanInIfConditionRuleFixerRector;
use Rector\Strict\Rector\Ternary\BooleanInTernaryOperatorRuleFixerRector;
use Rector\Strict\Rector\Ternary\DisallowedShortTernaryRuleFixerRector;
use Rector\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector;

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
    phpunitCodeQuality: false,
  )
  ->withRealPathReporting()
  ->withRootFiles()
  ->withSkip([
    EncapsedStringsToSprintfRector::class,
    RenameParamToMatchTypeRector::class,
    RenameVariableToMatchMethodCallReturnTypeRector::class,
    NullableCompareToNullRector::class,
    FlipTypeControlToUseExclusiveTypeRector::class,
    ExplicitBoolCompareRector::class,
    BooleanInTernaryOperatorRuleFixerRector::class,
    CompactToVariablesRector::class,
    CatchExceptionNameMatchingTypeRector::class,
    NewlineAfterStatementRector::class,
    BooleanInBooleanNotRuleFixerRector::class,
    BooleanInIfConditionRuleFixerRector::class,
    BinaryOpNullableToInstanceofRector::class,
    RenamePropertyToMatchTypeRector::class,
    DisallowedShortTernaryRuleFixerRector::class,
  ])
  ->withSkipPath(__DIR__ . '/vendor')
  ->withSkipPath(__DIR__ . '/node_modules')
  ->withSkipPath(__DIR__ . '/.git')
;
