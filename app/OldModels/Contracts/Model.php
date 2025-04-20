<?php

declare(strict_types=1);

namespace App\OldModels\Contracts;

use DateTimeInterface;
use JsonSerializable;

/**
 * @property-read ?int $id
 * @property-read ?string $registeredDate
 */
abstract class Model implements JsonSerializable
{
  private ?int $id = null;
  private ?DateTimeInterface $registeredDateTime = null;

  final public function setId(int $id): static
  {
    if ($this->id === null) {
      $this->id = $id;
    }

    return $this;
  }

  final public function setRegisteredDate(DateTimeInterface $datetime): static
  {
    if ($this->registeredDateTime === null) {
      $this->registeredDateTime = $datetime;
    }

    return $this;
  }

  final public function isEqualTo(self $model): bool
  {
    return $this->id === $model->id;
  }

  public function __get(string $property): null|int|string
  {
    return match ($property) {
      'id' => $this->id,
      'registeredDate' => $this->registeredDateTime->format('d/m/Y')
    };
  }

  public function jsonSerialize(): array
  {
    return [
      'id' => $this->id,
      'registeredDate' => $this->registeredDateTime?->format('d/m/Y'),
      'registeredDateImperialFormat' => $this->registeredDateTime?->format('Y-m-d'),
    ];
  }
}
