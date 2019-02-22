<?php

namespace App\Controller;

use App\Entity\Matching;
use App\Form\MatchingType;
use App\Repository\MatchingRepository;
use App\Repository\ChannelRepository;
use App\Repository\KeyWordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FeedValidator;
use App\Service\WordFilter;
/**
 * @Route("/matching")
 */
class MatchingController extends AbstractController
{
    /**
     * @Route("/", name="matching_index", methods={"GET"})
     */
    public function index(MatchingRepository $matchingRepository): Response
    {
        return $this->render('matching/index.html.twig', [
            'matchings' => $matchingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="matching_new", methods={"GET","POST"})
     */
    public function new(ChannelRepository $channelRepository,
            FeedValidator $feedValidator,
            WordFilter $wordFilter,
            KeyWordRepository $keyWordRepository
            )
    {
      $matching = new Matching;
      $channelList = $channelRepository->findAll();
      $keyWordList = $keyWordRepository->findAll();
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
          // array with SimplePie Objects
          $feedList[] = $feedValidator->validateFeed($channel->geturl());
        }
        $channel->setErrorNb($channel->getErrorNb()+ $i);
        $j = $j+$i; // total error count on this call
      }//end foreach

      $matching->setErrorNb($j);
      $matching->setCreatedAt(new \Datetime());

      // keywords without French typographic accents
      $filteredKw = $wordFilter->getFilter($keyWordList);

      foreach($feedList as $feed)
      {
        foreach($feed->get_items() as  $item)
        {
            // rss feed titles without French typographic accents
            $filteredTitle = $wordFilter->getFilter($item->get_title());
            // match rss feed titles with keywords
            if(preg_match("#\b.$filteredKw.\b#i", $filteredTitle,$matche)) //whole word case-insensitive
            {
              // return list per title filtered  rss items
              $itemList[] = $item;
            } //end if
        } //end foreach

      } //end foreach

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($matching);
      $entityManager->flush();

        return $this->render('match/index.html.twig', [
            'itemList' => $itemList,
        ]);
/*
        return $this->render('matching/new.html.twig', [
            'matching' => $matching,
            'form' => $form->createView(),
        ]); */
    }

    /**
     * @Route("/{id}", name="matching_show", methods={"GET"})
     */
    public function show(Matching $matching): Response
    {
        return $this->render('matching/show.html.twig', [
            'matching' => $matching,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="matching_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Matching $matching): Response
    {
        $form = $this->createForm(MatchingType::class, $matching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('matching_index', [
                'id' => $matching->getId(),
            ]);
        }

        return $this->render('matching/edit.html.twig', [
            'matching' => $matching,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="matching_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Matching $matching): Response
    {
        if ($this->isCsrfTokenValid('delete'.$matching->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($matching);
            $entityManager->flush();
        }

        return $this->redirectToRoute('matching_index');
    }
}
