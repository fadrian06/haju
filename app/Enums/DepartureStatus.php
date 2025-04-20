<?php

declare(strict_types=1);

namespace HAJU\Enums;

enum DepartureStatus: string
{
  case Healing = 'Curación';
  case Recovery = 'Mejoría';
  case Death = 'Muerte';
  case Autopsy = 'Autopsia';
  case Others = 'Otras causas';
}
