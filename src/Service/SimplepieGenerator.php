<?php
namespace App\Service;

use Volcagnomes\SimplePieBundle\Service\SimplePieService;

class SimplepieGenerator
{
  private $rss;

  public function __construct(SimplePieService $rss)
  {
    $this->rss = $rss;
  }
}
