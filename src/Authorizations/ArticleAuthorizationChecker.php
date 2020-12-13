<?php
declare(strict_types=1);

namespace App\Authorizations;


use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleAuthorizationChecker
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

  public function check(Article $article, string $method):void
  {
    $this->isAuthenticated();

    if($this->isMethodAllowed($method) &&
        $article->getAuthor()->getId() !== $this->user->getId()
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