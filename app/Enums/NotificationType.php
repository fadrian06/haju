<?php

declare(strict_types=1);

namespace HAJU\Enums;

enum NotificationType: string {
  case MESSAGE = 'message';
  case INFO = 'info';
  case ERROR = 'error';

  public function getBootstrapColor(): string {
    return match ($this) {
      self::MESSAGE => 'success',
      self::INFO => 'info',
      self::ERROR => 'danger',
    };
  }
}
