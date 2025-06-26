<?php



namespace HAJU\Tests\Unit\ValueObjects;

use HAJU\ValueObjects\Name;
use InvalidArgumentException;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NameTest extends TestCase
{
  #[Test]
  #[DataProvider('validNames')]
  public function acceptValidNames(string $name): void
  {
    $name = new Name($name, 'lastName');

    self::assertIsString($name->__toString());
  }

  #[Test]
  #[DataProvider('invalidNames')]
  public function throwsExceptionForInvalidNames(string $invalidName): void
  {
    self::expectException(InvalidArgumentException::class);

    new Name($invalidName, 'lastName');
  }

  public static function validNames(): Iterator
  {
    yield 'Del Carmen' => ['Del Carmen'];
    yield 'de briceño' => ['de briceño'];
    yield 'Rodríguez' => ['Rodríguez'];
  }

  public static function invalidNames(): Iterator
  {
    yield '' => [''];
    yield '123' => ['123'];
    yield 'Del Carmen 123' => ['Del Carmen 123'];
    yield 'Del Carmen!' => ['Del Carmen!'];
    yield 'Rodríguez$' => ['Rodríguez$'];
    yield 'Rodríguez${' => ['Rodríguez${'];
    yield 'Rodríguez$,' => ['Rodríguez$,'];
    yield 'Rodríguez$.' => ['Rodríguez$.'];
    yield 'Rodríguez$<' => ['Rodríguez$<'];
    yield 'Rodríguez$>' => ['Rodríguez$>'];
    yield 'Rodríguez?' => ['Rodríguez?'];
    yield 'Rodríguez¿' => ['Rodríguez¿'];
  }
}
