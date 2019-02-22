<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Channel;
use App\Entity\Match;
use App\Repository\ChannelRepository;
use SimplePie;
use App\Service\FeedValidator;
use App\Service\WordFilter;


class MatchController extends AbstractController
{
    /**
     * @Route("/match", name="match")
     */
    public function index(ChannelRepository $channelRepository,
            FeedValidator $feedValidator,
            WordFilter $wordFilter
     )
    {
      $kw = ['valls','iran','bête','pied','lol','victime','Christophe Dettinger','endométriose','arrêtez','Bârthês'];
      $match = new Match;
      $channelList = $channelRepository->findAll();
      $i=0; // initializes the channel error counter
      $j=0; // initializes the matching error counter
      foreach ($channelList as $channel)
      {
        $channel->setCallNb($channel->getCallNb()+1); // increments the call number of the channel
        if(!$feedValidator->validateFeed($channel->geturl()))
        {
          $i++; // increments the error counter
        }
        else
        {
          // array with valid RSS feeds
          $feedList[] = $feedValidator->validateFeed($channel->geturl());
        }
        $channel->setErrorNb($channel->getErrorNb()+ $i);
        $j = $j+$i; // total error count on this call
      }//end foreach

      $match->setErrorNb($j);
      $match->setCreatedAt(new \Datetime());

      // keywords without French typographic accents
      $filteredKw = $wordFilter->getFilter($kw);

      foreach($feedList as $feed)
      {
        foreach($feed->get_items() as  $item)
        {
            // rss feed titles without French typographic accents
            $filteredTitle = $wordFilter->getFilter($item->get_title());
            // match rss feed titles with keywords
            if(preg_match("#\b.$filteredKw.\b#i", $filteredTitle,$matche)) //whole word case-insensitive
            {

              $titleList[] = $item->get_title();
            } //end if
        } //end foreach

      } //end foreach

      $entityManager = $this->getDoctrine()->getManager();
      //$entityManager->persist($match);
      $entityManager->flush();

        return $this->render('match/index.html.twig', [
            'titleList' => $titleList,
        ]);
    }//end func
}//end class
