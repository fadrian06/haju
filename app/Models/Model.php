<?php

namespace App\Models;

abstract class Model {
  private ?int $id = null;

  function setId(int $id): self {
    if ($this->id === null) {
      $this->id = $id;
    }

    return $this;
  }

  function getId(): ?int {
    return $this->id;
  }
}
