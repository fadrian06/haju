<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use DateTime;

/**
 * @property-read ?int $id
 * @property-read ?string $registeredDate
 */
abstract class Model {
  private ?int $id = null;
  private ?DateTime $registeredDateTime = null;

  final public function setId(int $id): static {
    if ($this->id === null) {
      $this->id = $id;
    }

    return $this;
  }

  final public function setRegisteredDate(DateTime $datetime): static {
    if ($this->registeredDateTime === null) {
      $this->registeredDateTime = $datetime;
    }

    return $this;
  }

  final public function isEqualTo(self $model): bool {
    return $this->id === $model->id;
  }

  public function __get(string $property): null|int|string {
    return match ($property) {
      'id' => $this->id,
      'registeredDate' => $this->registeredDateTime->format('d/m/Y')
    };
  }
}
