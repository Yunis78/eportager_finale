<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProducerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DefaultController extends AbstractController
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
     * @Route("/", name="homepage")
     */
    public function index(ProducerRepository $producerRepository): Response
    {
        $products = $this->em->getRepository(Product::class)->findBy([],['dateCreated' => 'ASC'],4);

        return $this->render('components/pages/default/index.html.twig', [
            'products' => $products,
            'controller_name' => 'HomepageController',
            'producers' => $producerRepository->findLastNb(2,0),
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('components/pages/default/contact.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/faq", name="faq")
     */
    public function faq(): Response
    {
        return $this->render('components/pages/default/faq.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/concept", name="concept")
     */
    public function concept(): Response
    {
        return $this->render('components/pages/default/concept.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/accessCRUD", name="accessCRUD")
     */
    public function accessCRUD(): Response
    {
        return $this->render('components/pages/default/accessCRUD.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
