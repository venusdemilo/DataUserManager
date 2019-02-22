<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Form\ChannelType;
use App\Repository\ChannelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SimplePie;
use App\Service\FeedValidator;
/**
 * @Route("/channel")
 */
class ChannelController extends AbstractController
{
    /**
     * @Route("/", name="channel_index", methods={"GET"})
     */
    public function index(ChannelRepository $channelRepository): Response
    {
        return $this->render('channel/index.html.twig', [
            'channels' => $channelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="channel_new", methods={"GET","POST"})
     */
    public function new(Request $request , FeedValidator $feedValidator,ChannelRepository $channelRepository): Response
    {
        $channel = new Channel();
        $form = $this->createForm(ChannelType::class, $channel);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
          $url = $form->getData()->geturl();

          if(!$feedValidator->validateFeed($url))
          {
            $this->addFlash(
            'notice',
            'Une erreur s\'est produite en récupérant ce flux - enregistrement annulé '
            );
            return $this->render('channel/new.html.twig',
            [
                'channel' => $channel,
                'form' => $form->createView(),
            ]
          );
          }
          else
          {
            $feed = $feedValidator->validateFeed($url);
            $url = $channelRepository->findOneBy(array('url' => $url));
            if ($url == null) // elimination of duplicates url
            {
              $channel->setTitle($feed->get_title());
              $channel->setCreatedAt(new \Datetime());
              $channel->setActive(true);
              $channel->setCallNb(0);
              $channel->setErrorNb(0);

              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($channel);
              $entityManager->flush();
            }
            else
            {
                $this->addFlash(
                'notice',
                'Ce flux existe déjà'
                );
                return $this->render('channel/new.html.twig',
                [
                    'channel' => $channel,
                    'form' => $form->createView(),
                ]
              );
            }




            return $this->redirectToRoute('channel_index');
          }//end else ()


        } //end form submitted

        return $this->render('channel/new.html.twig', [
            'channel' => $channel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="channel_show", methods={"GET"})
     */
    public function show(Channel $channel): Response
    {
        return $this->render('channel/show.html.twig', [
            'channel' => $channel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="channel_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Channel $channel, FeedValidator $feedValidator): Response
    {
        $form = $this->createForm(ChannelType::class, $channel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $url = $form->getData()->geturl();
          if(!$feedValidator->validateFeed($url))
          {
            $this->addFlash(
            'notice',
            'Une erreur s\'est produite en récupérant ce flux.'
            );
            return $this->render('channel/new.html.twig',
            [
                'channel' => $channel,
                'form' => $form->createView(),
            ]
          );
          }
          else
          {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('channel_index', [
                'id' => $channel->getId(),
            ]);
          }


        }

        return $this->render('channel/edit.html.twig', [
            'channel' => $channel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="channel_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Channel $channel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$channel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($channel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('channel_index');
    }

    /**
     * @Route("/{id}/feeds", name="show_feeds", methods={"GET"})
     */
    public function showFeeds(Request $request, Channel $channel): Response
    {


        return $this->json(array('username' => 'jane.doe'));
    }




}
