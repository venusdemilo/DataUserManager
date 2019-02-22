<?php

namespace App\Controller;

use App\Entity\KeyWord;
use App\Form\KeyWordType;
use App\Repository\KeyWordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/key/word")
 */
class KeyWordController extends AbstractController
{
    /**
     * @Route("/", name="key_word_index", methods={"GET"})
     */
    public function index(KeyWordRepository $keyWordRepository): Response
    {
        return $this->render('key_word/index.html.twig', [
            'key_words' => $keyWordRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="key_word_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $keyWord = new KeyWord();
        $form = $this->createForm(KeyWordType::class, $keyWord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($keyWord);
            $entityManager->flush();

            return $this->redirectToRoute('key_word_index');
        }

        return $this->render('key_word/new.html.twig', [
            'key_word' => $keyWord,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="key_word_show", methods={"GET"})
     */
    public function show(KeyWord $keyWord): Response
    {
        return $this->render('key_word/show.html.twig', [
            'key_word' => $keyWord,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="key_word_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, KeyWord $keyWord): Response
    {
        $form = $this->createForm(KeyWordType::class, $keyWord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('key_word_index', [
                'id' => $keyWord->getId(),
            ]);
        }

        return $this->render('key_word/edit.html.twig', [
            'key_word' => $keyWord,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="key_word_delete", methods={"DELETE"})
     */
    public function delete(Request $request, KeyWord $keyWord): Response
    {
        if ($this->isCsrfTokenValid('delete'.$keyWord->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($keyWord);
            $entityManager->flush();
        }

        return $this->redirectToRoute('key_word_index');
    }
}
