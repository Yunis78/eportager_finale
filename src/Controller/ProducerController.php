<?php

namespace App\Controller;

use App\Entity\Producer;
use App\Form\ProducerType;
use App\Repository\ProducerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/producer")
 */
class ProducerController extends AbstractController
{
    /**
     * @Route("/", name="producer_index", methods={"GET"})
     */
    public function index(ProducerRepository $producerRepository): Response
    {
        return $this->render('producer/index.html.twig', [
            'producers' => $producerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="producer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $producer = new Producer();
        $form = $this->createForm(ProducerType::class, $producer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($producer);
            $entityManager->flush();

            return $this->redirectToRoute('producer_index');
        }

        return $this->render('producer/new.html.twig', [
            'producer' => $producer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="producer_show", methods={"GET"})
     */
    public function show(Producer $producer): Response
    {
        return $this->render('producer/show.html.twig', [
            'producer' => $producer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="producer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Producer $producer): Response
    {
        $form = $this->createForm(ProducerType::class, $producer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('producer_index');
        }

        return $this->render('producer/edit.html.twig', [
            'producer' => $producer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="producer_delete", methods={"POST"})
     */
    public function delete(Request $request, Producer $producer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$producer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($producer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('producer_index');
    }
}
