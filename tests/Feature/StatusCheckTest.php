<?php

declare(strict_types=1);

namespace HAJU\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;

final class StatusCheckTest extends FeatureTestCase {
  #[Test]
  public function apiIsRunning(): void {
    $response = self::$client->get('./api/status');
    self::assertSame(200, $response->getStatusCode());

    self::assertStringContainsString(
      'application/json',
      mb_strtolower($response->getHeaderLine('content-type'))
    );

    self::assertSame('{"status":"ok"}', (string) $response->getBody());
  }
}
