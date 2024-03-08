<?php

use App\Models\Date;
use App\Models\Exceptions\InvalidPhoneException;
use App\Models\Gender;
use App\Models\Phone;
use App\Models\ProfessionPrefix;
use App\Models\Role;
use App\Models\User;
use PharIo\Manifest\Email;
use PharIo\Manifest\InvalidEmailException;
use PharIo\Manifest\InvalidUrlException;
use PharIo\Manifest\Url;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {
  private const RAW_PASSWORD = 'test1234';
  private User $testUser;

  function setUp(): void {
    $this->testUser = new User(
      'Franyer',
      'Sánchez',
      new Date(6, 10, 2001),
      Gender::Male,
      Role::Director,
      ProfessionPrefix::Ing,
      28072391,
      'test1234'
    );
  }

  // function test_can_get_the_user_full_name(): void {
  function test_puede_obtener_el_nombre_completo_del_usuario(): void {
    self::assertSame('Franyer Sánchez', $this->testUser->getFullName());
  }

  // function test_it_encrypts_the_password_when_invalid_hash_is_given(): void {
  function test_encripta_la_constraseña_cuando_un_hash_inválido_es_dado(): void {
    $hash = password_hash(self::RAW_PASSWORD, PASSWORD_DEFAULT);

    self::assertNotSame(self::RAW_PASSWORD, $this->testUser->getPassword());
    self::assertSame($hash, (new User(
      'Franyer',
      'Sánchez',
      new Date(6, 10, 2001),
      Gender::Male,
      Role::Coordinator,
      null,
      28072391,
      $hash
    ))->getPassword());
  }

  // function test_ensure_id_is_readonly(): void {
  function test_asegura_que_el_id_es_de_sólo_lectura(): void {
    $user = clone $this->testUser;
    $user->setId(rand());

    self::assertNotSame($user->getId(), $this->testUser->getId());
  }

  // function test_it_returns_true_when_password_match_the_hash(): void {
  function test_retorna_verdadero_cuando_la_contraseña_coincide_con_el_hash_dado(): void {
    self::assertTrue($this->testUser->checkPassword(self::RAW_PASSWORD));
    self::assertFalse($this->testUser->checkPassword('test12345'));
  }

  #[DataProvider('phonesDataProvider')]
  // function test_can_create_an_user_with_a_valid_contact_info(string $raw, string $parsed): void {
  function test_puede_crear_un_usuario_con_información_de_contacto_válida(string $raw, string $parsed): void {
    $email = 'franyeradriansanchez@gmail.com';
    $avatar = 'https://github.com/fadrian06.png';

    $user = new User(
      'Franyer',
      'Sánchez',
      new Date(6, 10, 2001),
      Gender::Male,
      Role::Coordinator,
      ProfessionPrefix::Ing,
      28072391,
      'test1234',
      new Phone($raw),
      new Email($email),
      avatar: new Url($avatar)
    );

    self::assertSame($parsed, (string) $user->phone);
    self::assertSame($email, $user->email->asString());
    self::assertSame($avatar, $user->avatar->asString());
  }

  // function test_cannot_create_an_user_with_an_invalid_phone(): void {
  function test_no_puede_crear_un_usuario_con_un_teléfono_inválido(): void {
    self::expectException(InvalidPhoneException::class);
    self::expectExceptionMessage('Invalid phone "123"');

    new User(
      'Franyer',
      'Sánchez',
      new Date(6, 10, 2001),
      Gender::Male,
      Role::Coordinator,
      ProfessionPrefix::Ing,
      28072391,
      'test1234',
      new Phone('123')
    );
  }

  // function test_cannot_create_an_user_with_an_invalid_email(): void {
  function test_no_puede_crear_un_usuario_con_un_correo_inválido(): void {
    self::expectException(InvalidEmailException::class);

    new User(
      'Franyer',
      'Sánchez',
      new Date(6, 10, 2001),
      Gender::Male,
      Role::Coordinator,
      ProfessionPrefix::Ing,
      28072391,
      'test1234',
      email: new Email('franyeradriansanchez-gmail.com')
    );
  }

  // function test_cannot_create_an_user_with_an_invalid_avatar(): void {
  function test_no_puede_crear_un_usuario_con_un_avatar_inválido(): void {
    self::expectException(InvalidUrlException::class);

    new User(
      'Franyer',
      'Sánchez',
      new Date(6, 10, 2001),
      Gender::Male,
      Role::Coordinator,
      ProfessionPrefix::Ing,
      28072391,
      'test1234',
      avatar: new Url('myAvatar.jpg')
    );
  }

  static function phonesDataProvider(): array {
    return [
      '04165335826' => ['04165335826', '+58 416-5335826'],
      '0416533 5826' => ['0416533 5826', '+58 416-5335826'],
      '0416 5335826' => ['0416 5335826', '+58 416-5335826'],
      '0416 533 5826' => ['0416 533 5826', '+58 416-5335826'],
      '0416 533-5826' => ['0416 533-5826', '+58 416-5335826'],
      '0416-533 5826' => ['0416-533 5826', '+58 416-5335826'],
      '0416-5335826' => ['0416-5335826', '+58 416-5335826'],
      '0416533-5826' => ['0416533-5826', '+58 416-5335826'],
      '0416-533-5826' => ['0416-533-5826', '+58 416-5335826'],
      '+584165335826' => ['+584165335826', '+58 416-5335826']
    ];
  }
}
