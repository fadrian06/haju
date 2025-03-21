<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\ValueObjects\Exceptions\InvalidPhoneException;
use Stringable;

class Phone implements Stringable {
  private string $countryCode = '58';
  private readonly string $simPrefix;
  private readonly string $phoneNumber;

  /** @throws InvalidPhoneException */
  public function __construct(string $phone) {
    if (preg_match('/^(0?(?<simPrefix>\d{3}))(\s|-)?(\d{3})(\s|-)?(\d{4})$/', $phone, $matches)) {
      if (in_array($matches['simPrefix'], [416, 426, 414, 424])) {
        $this->countryCode = '58';
      }

      $this->simPrefix = $matches['simPrefix'];
      $this->phoneNumber = $matches[4] . $matches[6];

      return;
    }

    if (preg_match('/^\+(?<countryCode>\d{2,4})(\s|-)?(?<simPrefix>\d{3})(\s|-)?(\d{3})(\s|-)?(\d{4})$/', $phone, $matches)) {
      $this->countryCode = $matches['countryCode'];
      $this->simPrefix = $matches['simPrefix'];
      $this->phoneNumber = $matches[5] . $matches[7];

      return;
    }

    throw new InvalidPhoneException("Teléfono inválido \"{$phone}\"");
  }

  public function __toString(): string {
    return "+{$this->countryCode} {$this->simPrefix}-{$this->phoneNumber}";
  }

  public function toValidPhoneLink(): string {
    return $this->countryCode . $this->simPrefix . $this->phoneNumber;
  }
}
