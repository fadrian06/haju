<?php

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {
  private const RAW_PASSWORD = 'test1234';
  private User $testUser;

  function setUp(): void {
    $this->testUser = new User(28072391, 'test1234');
  }

  function test_it_encrypts_the_password_when_invalid_hash_is_given(): void {
    $hash = password_hash(self::RAW_PASSWORD, PASSWORD_DEFAULT);

    self::assertNotSame(self::RAW_PASSWORD, $this->testUser->getPassword());
    self::assertSame($hash, (new User(28072391, $hash))->getPassword());
  }

  function test_ensure_id_is_readonly(): void {
    $user = clone $this->testUser;
    $user->setId(rand());

    self::assertNotSame($user->getId(), $this->testUser->getId());
  }

  function test_it_returns_true_when_password_match_the_hash(): void {
    self::assertTrue($this->testUser->checkPassword(self::RAW_PASSWORD));
    self::assertFalse($this->testUser->checkPassword('test12345'));
  }
}
