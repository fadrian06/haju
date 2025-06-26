<?php

namespace HAJU\Tests\Feature;

use DOMDocument;
use PHPUnit\Framework\Attributes\Test;

final class LandingTest extends FeatureTestCase
{
  #[Test]
  public function itRendersLandingPage(): void
  {
    $response = self::$client->get('./');
    $domDocument = new DOMDocument();
    @$domDocument->loadHTML($response->getBody()->getContents());
    $title = $domDocument->getElementsByTagName('title')->item(0)->textContent;

    self::assertSame(200, $response->getStatusCode());
    self::assertSame('Hospital Antonio José Uzcátegui - HAJU', $title);
  }
}
