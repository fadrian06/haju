<?php

use App\ValueObjects\Name;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NameTest extends TestCase {
  #[Test]
  #[DataProvider('validNames')]
  function acceptValidNames(string $name): void {
    $name = new Name($name, 'lastName');

    self::assertIsString($name->__toString());
  }

  #[Test]
  #[DataProvider('invalidNames')]
  function throwsExceptionForInvalidNames(string $invalidName): void {
    self::expectException(InvalidArgumentException::class);
    new Name($invalidName, 'lastName');
  }

  static function validNames(): array {
    return [
      'Del Carmen' => ['Del Carmen'],
      'de briceño' => ['de briceño'],
      'Rodríguez' => ['Rodríguez'],
    ];
  }

  static function invalidNames(): array {
    return [
      '' => [''],
      '123' => ['123'],
      'Del Carmen 123' => ['Del Carmen 123'],
      'Del Carmen!' => ['Del Carmen!'],
      'Rodríguez$' => ['Rodríguez$'],
      'Rodríguez${' => ['Rodríguez${'],
      'Rodríguez$,' => ['Rodríguez$,'],
      'Rodríguez$.' => ['Rodríguez$.'],
      'Rodríguez$<' => ['Rodríguez$<'],
      'Rodríguez$>' => ['Rodríguez$>'],
      'Rodríguez?' => ['Rodríguez?'],
      'Rodríguez¿' => ['Rodríguez¿'],
    ];
  }
}
