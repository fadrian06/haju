<?php

namespace HAJU\Enums;

enum Gender: string
{
  use BackedEnum;

  case Male = 'Masculino';
  case Female = 'Femenino';
}
