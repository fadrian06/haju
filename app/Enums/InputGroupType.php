<?php

declare(strict_types=1);

namespace HAJU\Enums;

enum InputGroupType: string
{
  case TEXTAREA = 'textarea';
  case SELECT = 'select';
  case FILE = 'file';
  case CHECKBOX = 'checkbox';
  case TEXT = 'text';
  case DATE = 'date';
  case RADIO = 'radio';
  case MONTH = 'month';
  case NUMBER = 'number';
  case PASSWORD = 'password';
  case TEL = 'tel';
  case EMAIL = 'email';
  case URL = 'url';
}
