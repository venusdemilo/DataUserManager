<?php
namespace App\Service;
use SimplePie;

class FeedValidator
{

    private $simplePieCacheLocation;

    public function __construct($simplePieCacheLocation)
    {

      $this->simplePieCacheLocation = $simplePieCacheLocation;
    }

    public function validateFeed($urlFeed)
    {
    $feed = new SimplePie();
    $feed->set_cache_location($this->simplePieCacheLocation);
    $feed->set_feed_url($urlFeed);
    $feed->init();
    $feed->handle_content_type();
    if ($feed->error())
    {
    return false;
    }
    else
    {
      return $feed; // return a SimplePie object
    }
  }
}
