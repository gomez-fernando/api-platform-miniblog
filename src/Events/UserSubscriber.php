<?php
declare(strict_types=1);

namespace App\Events;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Authorizations\UserAuthorizationChecker;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSubscriber implements EventSubscriberInterface
{
  private array $methodNotAllowed = [
    Request::METHOD_GET,
    Request::METHOD_POST
  ];
  private UserAuthorizationChecker $userAuthorizationChecker;

  public function __construct(UserAuthorizationChecker $authorizationChecker)
  {
    $this->userAuthorizationChecker = $authorizationChecker;
  }
  public static function getSubscribedEvents()
  {
    return [
        KernelEvents::VIEW => ['check', EventPriorities::PRE_VALIDATE]
    ];
  }

  public function check(ViewEvent $event):void
  {
    $user = $event->getControllerResult();
    $method = $event->getRequest()->getMethod();

    if($user instanceof User
        && !in_array($method, $this->methodNotAllowed, true)
    ){
      $this->userAuthorizationChecker->check($user, $method);
      $user->setUpdatedAt(new \DateTimeImmutable());
    }
  }
}