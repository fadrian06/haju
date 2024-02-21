<?php

use App\Models\User;
use App\Repositories\Exceptions\ConnectionException;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Infraestructure\PDO\Connection;
use App\Repositories\Infraestructure\PDO\DBConnection;
use App\Repositories\Infraestructure\PDO\PDOUserRepository;
use PHPUnit\Framework\TestCase;

class PDOUserRepositoryTest extends TestCase {
  private PDOUserRepository $repository;
  private User $testUser;
  private string $testPassword;

  function setUp(): void {
    $this->testPassword = password_hash('test1234', PASSWORD_DEFAULT);
    $this->testUser = new User(28072391, $this->testPassword);
    $connection = new Connection(DBConnection::SQLite, ':memory:');

    $connection->instance()->query(<<<SQL
      CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        id_card INTEGER NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
      )
    SQL);

    $this->repository = new PDOUserRepository;
    $this->repository->setConnection($connection);
  }

  function test_it_throws_an_exception_when_users_table_is_not_defined(): void {
    $this->repository->setConnection(new Connection(DBConnection::SQLite, ':memory:'));

    self::expectException(ConnectionException::class);
    self::expectExceptionMessage('DB is not installed correctly');
    $this->repository->getAll();
  }

  function test_it_returns_an_empty_array_when_there_are_no_users(): void {
    self::assertSame([], $this->repository->getAll());
  }

  function test_it_returns_one_recent_registered_user(): void {
    $this->repository->save($this->testUser);

    $users = $this->repository->getAll();
    self::assertCount(1, $users);
    self::assertInstanceOf(User::class, $users[0]);
    self::assertSame(28072391, $users[0]->idCard);
  }

  function test_it_throws_an_exception_when_connection_is_not_provided(): void {
    $repository = new PDOUserRepository;

    self::expectException(ConnectionException::class);
    self::expectExceptionMessage('DB is not connected');
    $repository->getAll();
  }

  function test_can_save_an_user(): void {
    $this->repository->save($this->testUser);

    $user = $this->repository->getByIdCard(28072391);
    self::assertInstanceOf(User::class, $user);
    self::assertSame(28072391, $user->idCard);
    self::assertSame($this->testPassword, $user->getPassword());
  }

  function test_can_save_multiple_users(): void {
    $this->repository->save($this->testUser);
    $this->repository->save(new User(28072392, 'test1234'));

    $users = $this->repository->getAll();

    self::assertCount(2, $users);
    self::assertSame($this->testUser->idCard, $users[0]->idCard);
    self::assertSame(28072392, $users[1]->idCard);
  }

  function test_cannot_save_multiple_users_with_the_same_id_card(): void {
    $this->repository->save($this->testUser);

    self::expectException(DuplicatedIdCardException::class);
    self::expectExceptionMessage("ID card \"{$this->testUser->idCard}\" already exists");
    $this->repository->save(new User($this->testUser->idCard, 'test1234'));
  }

  function test_can_update_the_user_password(): void {
    $user = clone $this->testUser;
    self::assertTrue($user->checkPassword('test1234'));

    $this->repository->save($user);
    self::assertIsInt($user->getId());
    self::assertTrue($this->repository->getByIdCard($this->testUser->idCard)->checkPassword('test1234'));

    $user->setPassword('test1234updated');
    self::assertTrue($user->checkPassword('test1234updated'));

    $this->repository->save($user);
    self::assertTrue($this->repository->getByIdCard(28072391)->checkPassword('test1234updated'));
  }

  function test_it_retrieves_a_registered_user_by_id_card(): void {
    $this->repository->save($this->testUser);

    $user = $this->repository->getByIdCard($this->testUser->idCard);

    self::assertInstanceOf(User::class, $user);
    self::assertSame($this->testUser->idCard, $user->idCard);
    self::assertSame($this->testUser->getId(), $user->getId());
    self::assertSame($this->testUser->getPassword(), $user->getPassword());
  }

  function test_it_returns_null_when_an_unexistent_id_card_is_given(): void {
    $this->repository->save($this->testUser);

    self::assertNull($this->repository->getByIdCard($this->testUser->idCard + 1));
  }

  function test_it_retrieves_a_registered_user_by_id(): void {
    $this->repository->save($this->testUser);

    $user = $this->repository->getById($this->testUser->getId());

    self::assertInstanceOf(User::class, $user);
    self::assertSame($this->testUser->idCard, $user->idCard);
    self::assertSame($this->testUser->getId(), $user->getId());
    self::assertSame($this->testUser->getPassword(), $user->getPassword());
  }

  function test_it_returns_null_when_an_unexistent_id_is_given(): void {
    $this->repository->save($this->testUser);

    self::assertNull($this->repository->getById($this->testUser->getId() + 1));
  }
}
