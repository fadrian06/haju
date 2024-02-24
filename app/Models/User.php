<?php

namespace App\Models;

class User {
  private ?int $id = null;
  private string $password;

  function __construct(
    public string $firstName,
    public string $lastName,
    public readonly string $speciality,
    public ?GenrePrefix $prefix,
    public readonly int $idCard,
    string $password,
    public ?string $avatar = null
  ) {
    $this->setPassword($password);
  }

  function setId(int $id): self {
    if ($this->id === null) {
      $this->id = $id;
    }

    return $this;
  }

  function getId(): ?int {
    return $this->id;
  }

  function getPassword(): string {
    return $this->password;
  }

  function setPassword(string $password): self {
    $this->password = str_contains($password, '$2y$10')
      ? $password
      : password_hash($password, PASSWORD_DEFAULT);

    return $this;
  }

  function checkPassword(string $raw): bool {
    return password_verify($raw, $this->password);
  }

  function getFullName(): string {
    return "{$this->firstName} {$this->lastName}";
  }
}
