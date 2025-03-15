<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;

final class StatusCheckTest extends FeatureTestCase {
  #[Test]
  public function apiIsRunning(): void {
    $response = self::$client->get('./api/status');
    $body = $response->getBody()->getContents();

    echo $body, PHP_EOL;

    self::assertSame(
      200,
      $response->getStatusCode(),
      $body
    );

    self::assertStringContainsString(
      'application/json',
      mb_strtolower($response->getHeaderLine('content-type'))
    );

    self::assertSame('{"status":"ok"}', $body);
  }
}
