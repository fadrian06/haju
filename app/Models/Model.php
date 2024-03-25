<?php

namespace App\Models;

use DateTime;

abstract class Model {
  private ?int $id = null;
  private ?DateTime $registeredDateTime = null;

  function setId(int $id): static {
    if ($this->id === null) {
      $this->id = $id;
    }

    return $this;
  }

  function getId(): ?int {
    return $this->id;
  }

  function setRegisteredDate(DateTime $datetime): static {
    if ($this->registeredDateTime === null) {
      $this->registeredDateTime = $datetime;
    }

    return $this;
  }

  function getRegisteredDate(): string {
    return $this->registeredDateTime->format('d/m/Y');
  }
}
