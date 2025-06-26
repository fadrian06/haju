<?php

namespace HAJU\Models;

use HAJU\ValueObjects\LongName;

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
final class Hospital
{
  private LongName $name;

  public function __construct(
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

  public function setName(string $name): static
  {
    $this->name = new LongName($name, 'Nombre');

    return $this;
  }

  public function setAsic(string $asic): static
  {
    $this->asic = $asic;

    return $this;
    ;
  }

  public function setType(string $type): static
  {
    $this->type = $type;

    return $this;
    ;
  }

  public function setPlace(string $place): static
  {
    $this->place = $place;

    return $this;
    ;
  }

  public function setParish(string $parish): static
  {
    $this->parish = $parish;

    return $this;
    ;
  }

  public function setMunicipality(string $municipality): static
  {
    $this->municipality = $municipality;

    return $this;
    ;
  }

  public function setHealthDepartment(string $healthDepartment): static
  {
    $this->healthDepartment = $healthDepartment;

    return $this;
    ;
  }

  public function setRegion(string $region): static
  {
    $this->region = $region;

    return $this;
    ;
  }

  public function __get(string $property): null|string
  {
    return match ($property) {
      'name' => (string) $this->name,
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
