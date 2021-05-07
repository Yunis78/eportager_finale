<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProducerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




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
            'producers' => $producerRepository->findBy([], ['id'=>'DESC'], 2, 0),
            'nav' => ['active','','','',''],
        ]);
    }

    /**
     * @Route("/faq", name="faq")
     */
    public function faq(): Response
    {
        return $this->render('components/pages/default/faq.html.twig', [
            'controller_name' => 'DefaultController',
            'nav' => ['','','','','active'],
        ]);
    }

    /**
     * @Route("/concept", name="concept")
     */
    public function concept(): Response
    {
        return $this->render('components/pages/default/concept.html.twig', [
            'controller_name' => 'DefaultController',
            'nav' => ['','','','active',''],
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
