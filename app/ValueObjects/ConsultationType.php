<?php

namespace App\ValueObjects;

enum ConsultationType: string {
  case FirstTime = 'P';
  case Associated = 'A';
  case Succesive = 'S';
}
