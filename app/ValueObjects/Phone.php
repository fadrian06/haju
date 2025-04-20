<?php

declare(strict_types=1);

namespace HAJU\ValueObjects;

use HAJU\ValueObjects\Exceptions\InvalidPhoneException;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Stringable;

final readonly class Phone implements Stringable {
  private PhoneNumber $phoneNumber;

  /** @throws InvalidPhoneException */
  public function __construct(string $phone) {
    if (strlen($phone) < 11) {
      throw new InvalidPhoneException('Teléfono inválido');
    }

    try {
      $this->phoneNumber = PhoneNumberUtil::getInstance()->parse($phone, 'VE');
    } catch (NumberParseException) {
      throw new InvalidPhoneException('Teléfono inválido');
    }
  }

  public function __toString(): string {
    return PhoneNumberUtil::getInstance()->format(
      $this->phoneNumber,
      PhoneNumberFormat::INTERNATIONAL,
    );
  }

  public function toValidPhoneLink(): string {
    return PhoneNumberUtil::getInstance()->format(
      $this->phoneNumber,
      PhoneNumberFormat::E164,
    );
  }
}
