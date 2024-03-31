<?php

namespace App\Models;

use App\ValueObjects\LongName;

class Hospital {
  private LongName $name;

  function __construct(
    string $name,
    private string $asic,
    private string $type,
    private string $place,
    private string $parish,
    private string $municipality,
    private string $healthDepartment,
    private string $region
  ) {
    $this->setName($name);
  }

  function getName(): string {
    return $this->name;
  }

  function setName(string $name): static {
    $this->name = new LongName($name, 'Nombre');

    return $this;
  }

  function getAsic(): string {
    return $this->asic;
  }

  function getType(): string{
    return $this->type;
  }

  function getPlace(): string {
    return $this->place;
  }

  function getParish(): string {
    return $this->parish;
  }

  function getMunicipality(): string {
    return $this->municipality;
  }

  function getHealthDepartment(): string {
    return $this->healthDepartment;
  }

  function getRegion(): string {
    return $this->region;
  }

  function setAsic(string $asic): static {
    $this->asic = $asic;

    return $this;;
  }

  function setType(string $type): static {
    $this->type = $type;

    return $this;;
  }

  function setPlace(string $place): static {
    $this->place = $place;

    return $this;;
  }

  function setParish(string $parish): static {
    $this->parish = $parish;

    return $this;;
  }

  function setMunicipality(string $municipality): static {
    $this->municipality = $municipality;

    return $this;;
  }

  function setHealthDepartment(string $healthDepartment): static {
    $this->healthDepartment = $healthDepartment;

    return $this;;
  }

  function setRegion(string $region): static {
    $this->region = $region;

    return $this;;
  }
}
