<?php

declare(strict_types=1);

namespace HAJU\Enums;

enum ToastPosition {
  case TOP_LEFT;
  case TOP_RIGHT;
  case BOTTOM_LEFT;
  case BOTTOM_RIGHT;

  public function getBootstrapClasses(): string {
    return 'position-fixed ' . match ($this) {
      self::TOP_LEFT => 'top-0 start-0',
      self::TOP_RIGHT => 'top-0 end-0',
      self::BOTTOM_LEFT => 'bottom-0 start-0',
      self::BOTTOM_RIGHT => 'bottom-0 end-0',
    };
  }
}
