<?php
declare(strict_types=1);

namespace App\Tests\Func;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleTest extends AbstractEndPoint
{
  public function testArticles(): void
  {
    $response = $this->getResponseFromRequest(
        Request::METHOD_GET,
        '/api/articles',
        '',
        [],
        false
    );

    $responseContent = $response->getContent();
    $responseDecoded = json_decode($responseContent);

    self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    self::assertJson($responseContent);
    self::assertNotEmpty($responseDecoded);
  }
}