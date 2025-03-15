<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;

final class StatusCheckTest extends FeatureTestCase {
  #[Test]
  public function apiIsRunning(): void {
    $response = self::$client->get('./api/status');

    $this->assertSame(200, $response->getStatusCode());

    $this->assertStringContainsString('application/json', mb_strtolower($response->getHeaderLine('content-type')));

    $this->assertSame('{"status":"ok"}', $response->getBody()->getContents());
  }
}
