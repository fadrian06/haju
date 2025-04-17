<?php

declare(strict_types=1);

namespace App\Enums;

enum InputGroupType: string {
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

  public function isCheckbox(): bool {
    return $this === self::CHECKBOX;
  }

  public function isRadio(): bool {
    return $this === self::RADIO;
  }

  public function isTextarea(): bool {
    return $this === self::TEXTAREA;
  }

  public function isFile(): bool {
    return $this === self::FILE;
  }

  public function isSelect(): bool {
    return $this === self::SELECT;
  }

  public function isDate(): bool {
    return $this === self::DATE;
  }
}
