<?php

namespace App\Models\Contracts;

use DateTime;

/**
 * @property-read ?int $id
 * @property-read ?DateTime $registeredDate
 */
abstract class Model {
  private ?int $id = null;
  private ?DateTime $registeredDate = null;

  function setId(int $id): static {
    if ($this->id === null) {
      $this->id = $id;
    }

    return $this;
  }

  /** @deprecated */
  function getId(): ?int {
    return $this->id;
  }

  function setRegisteredDate(DateTime $datetime): static {
    if ($this->registeredDateTime === null) {
      $this->registeredDate = $datetime;
    }

    return $this;
  }

  /** @deprecated */
  function getRegisteredDate(): string {
    return $this->registeredDate->format('d/m/Y');
  }

  function __get(string $property): null|int|string {
    return match ($property) {
      'id' => $this->id,
      'registeredDate' => $this->registeredDate->format('d/m/Y'),
      default => null
    };
  }
}
