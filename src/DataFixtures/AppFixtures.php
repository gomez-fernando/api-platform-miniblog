<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
  const DEFAULT_USER = ['email' => 'fernando@fernando.com', 'password' => '12'];

  private UserPasswordEncoderInterface $encoder;

  public function __construct(UserPasswordEncoderInterface $encoder)
  {
    $this->encoder = $encoder;
  }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $defaultUser = new User();
        $passHash = $this->encoder->encodePassword($defaultUser, self::DEFAULT_USER['password']);

        $defaultUser->setEmail(self::DEFAULT_USER['email'])
                    ->setPassword($passHash);

        $manager->persist($defaultUser);

        for($u = 0; $u < 10; $u++){
          $user = new User();

          $passHash = $this->encoder->encodePassword($user, '12');

          $user->setEmail($faker->email)
                ->setPassword($passHash);

          if($u % 3 === 0){
            $user->setStatus(false)
                  ->setAge(23);
          }

          $manager->persist($user);

          for($a = 0; $a < random_int(5, 15); $a++){
            $article = (new Article())
                ->setAuthor($user)
                ->setContent($faker->text(300))
                ->setName($faker->text(40));

            $manager->persist($article);
          }

        }

        $manager->flush();
    }
}
