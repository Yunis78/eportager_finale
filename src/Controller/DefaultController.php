<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Producer;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormFactoryInterface;

class DefaultController
{
        /**
     * @var Environment
     */
    private $twig;
    
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var EntityManagerInterface
     */
    private $router;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(Environment $twig, EntityManagerInterface $em, RouterInterface $router, FormFactoryInterface $formFactory)
    {
        $this->twig = $twig;
        $this->em = $em;
        $this->router = $router;
        $this->formFactory = $formFactory;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        $products = $this->em->getRepository(Product::class)->findBy([],['dateCreated' => 'ASC'],4);

        return new Response($this->twig->render('components/pages/default/index.html.twig', [
            'products' => $products,
            'producers' =>  $this->em->getRepository(Producer::class)->findBy([], ['id'=>'DESC'], 2, 0),
        ]));
    }

    /**
     * @Route("/faq", name="faq")
     */
    public function faq()
    {
        return new Response($this->twig->render('components/pages/default/faq.html.twig', [
            'controller_name' => 'DefaultController',
        ]));
    }

    /**
     * @Route("/concept", name="concept")
     */
    public function concept()
    {
        return new Response($this->twig->render('components/pages/default/concept.html.twig', [
            'controller_name' => 'DefaultController',
        ]));
    }

    /**
     * @Route("/accessCRUD", name="accessCRUD")
     */
    public function accessCRUD()
    {
        return new Response($this->twig->render('components/pages/default/accessCRUD.html.twig', [
            'controller_name' => 'DefaultController',
        ]));
    }
}
