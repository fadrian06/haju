<?php

namespace App\Models\Contracts;

use DateTime;

/**
 * @property-read ?int $id
 * @property-read ?string $registeredDate
 */
abstract class Model {
  private ?int $id = null;
  private ?DateTime $registeredDateTime = null;

  function setId(int $id): static {
    if ($this->id === null) {
      $this->id = $id;
    }

    return $this;
  }

  function setRegisteredDate(DateTime $datetime): static {
    if ($this->registeredDateTime === null) {
      $this->registeredDateTime = $datetime;
    }

    return $this;
  }

  function __get(string $property): null|int|string {
    return match ($property) {
      'id' => $this->id,
      'registeredDate' => $this->registeredDateTime->format('d/m/Y')
    };
  }
}
