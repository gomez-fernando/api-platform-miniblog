<?php
declare(strict_types=1);

namespace App\Authorizations;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserAuthorizationChecker
{
  private array $methodNotAllowed = [
      Request::METHOD_PUT,
      Request::METHOD_PATCH,
      Request::METHOD_DELETE
  ];
  private ?UserInterface $user;

  public function __construct(Security $security)
  {
    $this->user = $security->getUser();
  }

  public function check(UserInterface $user, string $method):void
  {
    $this->isAuthenticated();

    if($this->isMethodAllowed($method) &&
        $user->getId() !== $this->user->getId()
    ){
      $errorMessasge = "It's not your resource";
      throw new UnauthorizedHttpException($errorMessasge, $errorMessasge);
    }
  }

  public function isAuthenticated(): void
  {
    if(null === $this->user){
      $errorMessasge = "You are not authenticated";
      throw new UnauthorizedHttpException($errorMessasge, $errorMessasge);
    }
  }

  public function isMethodAllowed(string $method): bool
  {
    return in_array($method, $this->methodNotAllowed, true);
  }
}