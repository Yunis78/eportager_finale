<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Producer;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\ProducerType;
use App\Repository\ProducerRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct( EntityManagerInterface $em )
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="front_producteurs")
     */
    public function front_producteurs(ProducerRepository $producerRepository): Response
    {
        return $this->render('components/pages/front_producteurs/index.html.twig', [
            // 'controller_name' => 'FrontProducteursController',
            'producers' => $producerRepository->findAll(),
        ]);
    }

       /**
     * @Route("/produdu", name="front_produdu")
     */
    public function front_porodudu (ProducerRepository $producerRepository): Response
    {
        return $this->render('components/pages/front_producteurs/produdu.html.twig', [
            // 'controller_name' => 'FrontProducteursController',
            'producers' => $producerRepository->findAll(),
        ]);
    }

    // même convention de nommage que pour Product

    /**
     * @Route("/producteurs", name="producer_index", methods={"GET"})
     */
    public function index(ProducerRepository $producerRepository): Response
    {
        return $this->render('components/pages/producer/index.html.twig', [
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
            
            $user = $this->em->getRepository(User::class)->find($this->getUser());

            $user->setRoles(["ROLE_PRODUCER"]);

            $producer->setUser($this->getUser());
            
            
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($producer);
            $entityManager->flush();

            return $this->redirectToRoute('producer_index');
        }

        return $this->render('components/pages/producer/new.html.twig', [
            'producer' => $producer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="producer_show", methods={"GET","POST"})
     */
    public function show(Request $request, Producer $producer, int $id): Response
    {

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $producer = $this->em->getRepository(Producer::class)->find($producer);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setUser($this->getUser());
            $comment->setProducer($producer);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('producer_show', ['id' => $id]);
        }

        return $this->render('components/pages/producer/show.html.twig', [
            'producer' => $producer,
            'comments' => $this->em->getRepository(Comment::class)->findBy(['producer'=> $producer->getId()]),
            'form' => $form->createView(),
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

        return $this->render('components/pages/producer/edit.html.twig', [
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
