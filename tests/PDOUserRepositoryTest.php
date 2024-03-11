<?php

use App\Models\Date;
use App\Models\Gender;
use App\Models\Phone;
use App\Models\ProfessionPrefix;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Exceptions\ConnectionException;
use App\Repositories\Exceptions\DuplicatedAvatarsException;
use App\Repositories\Exceptions\DuplicatedEmailsException;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;
use App\Repositories\Exceptions\DuplicatedPhonesException;
use App\Repositories\Infraestructure\PDO\Connection;
use App\Repositories\Infraestructure\PDO\DBConnection;
use App\Repositories\Infraestructure\PDO\PDOUserRepository;
use PharIo\Manifest\Email;
use PharIo\Manifest\Url;
use PHPUnit\Framework\TestCase;

class PDOUserRepositoryTest extends TestCase {
  private PDOUserRepository $repository;
  private User $testUser;
  private string $testPassword;

  function setUp(): void {
    $this->testPassword = password_hash('test1234', PASSWORD_DEFAULT);

    $this->testUser = new User(
      'Franyer',
      'Sánchez',
      new Date(6, 10, 2001),
      Gender::Male,
      Role::Coordinator,
      ProfessionPrefix::Ing,
      28072391,
      $this->testPassword,
      new Phone('+584165335826'),
      new Email('franyeradriansanchez@gmail.com'),
      'El Pinar, Estado Mérida',
      new Url('https://github.com/fadrian06.png')
    );

    $connection = new Connection(DBConnection::SQLite, ':memory:');

    $queries = explode(
      ';',
      file_get_contents(__DIR__ . '/../app/database/init.sqlite.sql')
    );

    foreach ($queries as $query) {
      $connection->instance()->query($query);
    }

    $this->repository = new PDOUserRepository;
    $this->repository->setConnection($connection);
  }

  // function test_it_throws_an_exception_when_users_table_is_not_defined(): void {
  function test_lanza_un_error_cuando_la_tabla_usuarios_no_está_definida(): void {
    $this->repository->setConnection(new Connection(DBConnection::SQLite, ':memory:'));

    self::expectException(ConnectionException::class);
    self::expectExceptionMessage('DB is not installed correctly');
    $this->repository->getAll();
  }

  // function test_it_returns_an_empty_array_when_there_are_no_users(): void {
  function test_retorna_una_lista_vacía_cuando_no_hay_usuarios(): void {
    self::assertSame([], $this->repository->getAll());
  }

  // function test_it_returns_one_recent_registered_user(): void {
  function test_retorna_un_usuario_registrado_recientemente(): void {
    $this->repository->save($this->testUser);

    $users = $this->repository->getAll();
    self::assertCount(1, $users);
    self::assertInstanceOf(User::class, $users[0]);
    self::assertEquals($this->testUser, $users[0]);
  }

  // function test_it_throws_an_exception_when_connection_is_not_provided(): void {
  function test_lanza_un_error_cuando_la_conexión_no_es_proveída(): void {
    $repository = new PDOUserRepository;

    self::expectException(ConnectionException::class);
    self::expectExceptionMessage('DB is not connected');
    $repository->getAll();
  }

  function test_can_save_an_user(): void {
    $this->repository->save($this->testUser);

    $user = $this->repository->getByIdCard(28072391);
    self::assertInstanceOf(User::class, $user);
    self::assertEquals($this->testUser, $user);
  }

  // function test_can_save_multiple_users(): void {
  function test_puede_guardar_múltiples_usuarios(): void {
    $user2 = new User(
      'Franyer',
      'Guillén',
      new Date(6, 10, 2001),
      Gender::Male,
      Role::Coordinator,
      ProfessionPrefix::Ing,
      28072392,
      'test1234'
    );

    $this->repository->save($this->testUser);
    $this->repository->save($user2);

    $users = $this->repository->getAll();

    self::assertCount(2, $users);
    self::assertEquals($this->testUser, $users[0]);
    self::assertEquals($user2, $users[1]);
  }

  // function test_cannot_save_multiple_users_with_the_same_id_card(): void {
  function test_no_puede_guardar_múltiples_usuarios_con_la_misma_cédula(): void {
    $this->repository->save($this->testUser);

    self::expectException(DuplicatedIdCardException::class);
    self::expectExceptionMessage("ID card \"{$this->testUser->idCard}\" already exists");

    $this->repository->save(new User(
      $this->testUser->firstName,
      'Guillén',
      $this->testUser->birthDate,
      $this->testUser->gender,
      $this->testUser->role,
      $this->testUser->prefix,
      $this->testUser->idCard,
      'test1234'
    ));
  }

  // function test_cannot_save_multiple_users_with_the_same_names(): void {
  function test_no_puede_guardar_múltiples_usuarios_con_los_mismos_nombres(): void {
    $this->repository->save($this->testUser);

    self::expectException(DuplicatedNamesException::class);
    self::expectExceptionMessage("User \"{$this->testUser->getFullName()}\" already exists");

    $this->repository->save(new User(
      $this->testUser->firstName,
      'Sánchez',
      $this->testUser->birthDate,
      $this->testUser->gender,
      $this->testUser->role,
      $this->testUser->prefix,
      $this->testUser->idCard,
      'test1234'
    ));
  }

  // function test_cannot_save_multiple_users_with_the_same_phones(): void {
  function test_no_puede_guardar_múltiples_usuarios_con_el_mismo_teléfono(): void {
    $this->repository->save($this->testUser);

    self::expectException(DuplicatedPhonesException::class);
    self::expectExceptionMessage("Phone \"{$this->testUser->phone}\" already exists");

    $this->repository->save(new User(
      $this->testUser->firstName,
      'Guillén',
      $this->testUser->birthDate,
      $this->testUser->gender,
      $this->testUser->role,
      $this->testUser->prefix,
      $this->testUser->idCard,
      'test1234',
      $this->testUser->phone
    ));
  }

  // function test_cannot_save_multiple_users_with_the_same_emails(): void {
  function test_no_puede_guardar_múltiples_usuarios_con_el_mismo_correo(): void {
    $this->repository->save($this->testUser);

    self::expectException(DuplicatedEmailsException::class);
    self::expectExceptionMessage("Email \"{$this->testUser->email->asString()}\" already exists");

    $this->repository->save(new User(
      $this->testUser->firstName,
      'Guillén',
      $this->testUser->birthDate,
      $this->testUser->gender,
      $this->testUser->role,
      $this->testUser->prefix,
      $this->testUser->idCard,
      'test1234',
      email: $this->testUser->email
    ));
  }

  // function test_cannot_save_multiple_users_with_the_same_avatars(): void {
  function test_no_puede_guardar_múltiples_usuarios_con_el_mismo_avatar(): void {
    $this->repository->save($this->testUser);

    self::expectException(DuplicatedAvatarsException::class);
    self::expectExceptionMessage("Avatar \"{$this->testUser->avatar->asString()}\" already exists");

    $this->repository->save(new User(
      $this->testUser->firstName,
      'Guillén',
      $this->testUser->birthDate,
      $this->testUser->gender,
      $this->testUser->role,
      $this->testUser->prefix,
      $this->testUser->idCard,
      'test1234',
      avatar: $this->testUser->avatar
    ));
  }

  // function test_can_update_the_user_password(): void {
  function test_puede_actualizar_la_contraseña_del_usuario(): void {
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

  // function test_it_retrieves_a_registered_user_by_id_card(): void {
  function test_recupera_un_usuario_registrado_por_cédula(): void {
    $this->repository->save($this->testUser);

    $user = $this->repository->getByIdCard($this->testUser->idCard);

    self::assertInstanceOf(User::class, $user);
    self::assertSame($this->testUser->idCard, $user->idCard);
    self::assertSame($this->testUser->getId(), $user->getId());
    self::assertSame($this->testUser->getPassword(), $user->getPassword());
  }

  // function test_it_returns_null_when_an_unexistent_id_card_is_searched(): void {
  function test_retorna_vacío_cuando_una_cédula_inexistente_es_buscada(): void {
    $this->repository->save($this->testUser);

    self::assertNull($this->repository->getByIdCard($this->testUser->idCard + 1));
  }

  // function test_it_retrieves_a_registered_user_by_id(): void {
  function test_recupera_un_usuario_registrado_por_id(): void {
    $this->repository->save($this->testUser);

    $user = $this->repository->getById($this->testUser->getId());

    self::assertInstanceOf(User::class, $user);
    self::assertSame($this->testUser->idCard, $user->idCard);
    self::assertSame($this->testUser->getId(), $user->getId());
    self::assertSame($this->testUser->getPassword(), $user->getPassword());
  }

  // function test_it_returns_null_when_an_unexistent_id_is_given(): void {
  function test_retorna_vacío_cuando_un_id_inexistente_es_buscado(): void {
    $this->repository->save($this->testUser);

    self::assertNull($this->repository->getById($this->testUser->getId() + 1));
  }
}
