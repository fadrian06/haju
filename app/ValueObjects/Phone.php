<?php

namespace App\ValueObjects;

use App\ValueObjects\Exceptions\InvalidPhoneException;
use Stringable;

readonly class Phone implements Stringable {
  private string $countryCode;
  private string $simPrefix;
  private string $phoneNumber;

  /** @throws InvalidPhoneException */
  function __construct(string $phone) {
    if (preg_match('/^(0?(?<simPrefix>\d{3}))(\s|-)?(\d{3})(\s|-)?(\d{4})$/', $phone, $matches)) {
      if (in_array($matches['simPrefix'], [416, 426, 414, 424])) {
        $this->countryCode = '58';
      }

      $this->simPrefix = $matches['simPrefix'];
      $this->phoneNumber = $matches[4] . $matches[6];

      return;
    }

    if (preg_match('/^\+(?<countryCode>\d{2})(\s|-)?(?<simPrefix>\d{3})(\s|-)?(\d{3})(\s|-)?(\d{4})$/', $phone, $matches)) {
      $this->countryCode = $matches['countryCode'];
      $this->simPrefix = $matches['simPrefix'];
      $this->phoneNumber = $matches[5] . $matches[7];

      return;
    }

    throw new InvalidPhoneException("Teléfono inválido \"$phone\"");
  }

  function __toString(): string {
    return "+{$this->countryCode} {$this->simPrefix}-{$this->phoneNumber}";
  }

  function toValidPhoneLink(): string {
    return $this->countryCode . $this->simPrefix . $this->phoneNumber;
  }
}
