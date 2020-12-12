<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Article;

class ArticleUpdatedAt
{
  public function __invoke(Article $data): Article
  {
//    dd($data);
    $data->setUpdatedAt(new \DateTimeImmutable("tomorrow"));
    return $data;
  }
}