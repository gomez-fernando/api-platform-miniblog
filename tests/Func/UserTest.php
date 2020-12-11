<?php
declare(strict_types=1);

namespace App\Tests\Func;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Faker\Factory;

class UserTest extends AbstractEndPoint
{
  private string $userPayload = '{"email": "%s", "password": "password"}';

  public function testGetUsers(): void
  {
//    dd($this->getResponseFromRequest(Request::METHOD_GET, 'api/users'));
    $response = $this->getResponseFromRequest(Request::METHOD_GET, 'api/users');
    $responseContent = $response->getContent();
    $responseDecoded = json_decode($responseContent);

    self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    self::assertJson($responseContent);
    self::assertNotEmpty($responseDecoded);
  }

  public function testPostUser(): void
  {
//    dd($this->getResponseFromRequest(Request::METHOD_GET, 'api/users'));
    $response = $this->getResponseFromRequest(
        Request::METHOD_GET,
        'api/users',
        $this->getPayload()
            );
    $responseContent = $response->getContent();
    $responseDecoded = json_decode($responseContent);

//    dd($responseDecoded);

    self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    self::assertJson($responseContent);
    self::assertNotEmpty($responseDecoded);
  }

  private function getPayload(): string
  {
    $faker = Factory::create();

    return sprintf($this->userPayload, $faker->email);
  }
}