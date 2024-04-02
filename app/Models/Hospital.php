<?php

namespace App\Models;

use App\ValueObjects\LongName;

/**
 * @property-read string $name
 * @property-read string $asic,
 * @property-read string $type,
 * @property-read string $place
 * @property-read string $parish
 * @property-read string $municipality
 * @property-read string $healthDepartment
 * @property-read string $region
 */
final class Hospital {
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

  /** @deprecated */
  function getName(): string {
    return $this->name;
  }

  function setName(string $name): static {
    $this->name = new LongName($name, 'Nombre');

    return $this;
  }

  /** @deprecated */
  function getAsic(): string {
    return $this->asic;
  }

  /** @deprecated */
  function getType(): string{
    return $this->type;
  }

  /** @deprecated */
  function getPlace(): string {
    return $this->place;
  }

  /** @deprecated */
  function getParish(): string {
    return $this->parish;
  }

  /** @deprecated */
  function getMunicipality(): string {
    return $this->municipality;
  }

  /** @deprecated */
  function getHealthDepartment(): string {
    return $this->healthDepartment;
  }

  /** @deprecated */
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

  function __get(string $property): null|string {
    return match ($property) {
      'name' => $this->name,
      'asic' => $this->asic,
      'type' => $this->type,
      'place' => $this->place,
      'parish' => $this->parish,
      'municipality' => $this->municipality,
      'healthDepartment' => $this->healthDepartment,
      'region' => $this->region,
      default => null
    };
  }
}
