<?php
declare(strict_types=1);

namespace App\Tests\Unit;


use App\Entity\Article;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
  private Article $article;

  protected function setUp()
  {
    parent::setUp();

    $this->article = new Article();
  }

  public function testGetName(): void
  {
    $value = 'Name for testing';

    $response = $this->article->setName($value);
    self::assertInstanceOf(Article::class, $response);
    self::assertEquals($value, $this->article->getName());
  }

  public function testGetContent(): void
  {
    $value = 'Content for testing';

    $response = $this->article->setContent($value);
    self::assertInstanceOf(Article::class, $response);
    self::assertEquals($value, $this->article->getContent());
  }

  public function testGetAuthor(): void
  {
    $value = new User();

    $response = $this->article->setAuthor($value);
    self::assertInstanceOf(Article::class, $response);
    self::assertInstanceOf(User::class, $this->article->getAuthor());
  }
}