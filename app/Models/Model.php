<?php

namespace App\Models;

use DateTime;

abstract class Model {
  private ?int $id = null;
  private ?DateTime $registered = null;

  function setId(int $id): static {
    if ($this->id === null) {
      $this->id = $id;
    }

    return $this;
  }

  function getId(): ?int {
    return $this->id;
  }

  function setRegistered(DateTime $datetime): static {
    if ($this->registered === null) {
      $this->registered = $datetime;
    }

    return $this;
  }

  function getRegisteredDate(): string {
    return $this->registered->format('d/m/Y');
  }
}
