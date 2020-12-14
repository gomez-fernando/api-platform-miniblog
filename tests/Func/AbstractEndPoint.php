<?php
declare(strict_types=1);

namespace App\Tests\Func;


use App\DataFixtures\AppFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractEndPoint extends WebTestCase
{
  protected array $serverInformations = ['ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'];
  protected string $tokenNotFound = 'JWT Token not found';
  protected string $notYourResource = 'It\'s not your resource';
  protected string $loginPayload = '{"email:" "%s", "password": "%s"}';

  public function getResponseFromRequest(string $method,
                                         string $uri,
                                         string $payload = '',
                                        array $parameter = [],
                                        bool $withAuthentification = true
  ): Response {

    $client = $this->createAuthenticationClient(($withAuthentification));

    $client->request(
        $method,
        $uri . '.json',
        $parameter,
        [],
        $this->serverInformations,
        $payload
    );

    return $client->getResponse();
  }

  protected function createAuthenticationClient(bool $withAuthentication): KernelBrowser
  {
    $client = static::createClient();

    if(!$withAuthentication){
      return $client;
    }

    $client->request(
        Request::METHOD_POST,
        '/api/login_check',
        [],
        [],
        $this->serverInformations,
        sprintf($this->loginPayload, AppFixtures::DEFAULT_USER['email'], AppFixtures::DEFAULT_USER['password'])
    );

    $data = json_decode($client->getResponse()->getContent() , true);

    $client->setServerParameter('HTTP_authorization', sprintf('Bearer %s', $data['token']));

    return $client;
  }
}