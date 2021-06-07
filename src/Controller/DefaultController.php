<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Producer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

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
        $products = $this->em->getRepository(Product::class)->findBy([], ['dateCreated' => 'ASC'], 4);

        return new Response($this->twig->render('components/pages/default/index.html.twig', [
            'products' => $products,
            'producers' =>  $this->em->getRepository(Producer::class)->findBy([], ['id' => 'DESC'], 2, 0),
        ]));
    }

     /**
     * @Route("/map", name="map")
     */
    public function map()
    {
        $producers = $this->em->getRepository(Producer::class)->findAll();
        $positions = [];
        foreach ( $producers as $producer ) {
            
            $addresstreet = $producer->getAddressStreet();
            $AddressZipcode = $producer->getAddressZipcode();
            $AddressCity = $producer->getAddressCity();
            $File = $producer->getFile();
            $positions[] = [
                'file' => $File,
                'name' => $producer->getName(),
                'address' => $addresstreet.' '.$AddressZipcode.' '.$AddressCity,
                'description' => $producer->getDescription(),
                'longitude' => $producer->getLongitude(),
                'latitude' => $producer->getLatitude(),
            ];
        }

        $products = $this->em->getRepository(Product::class)->findBy([], ['dateCreated' => 'ASC'], 4);

        return new Response($this->twig->render('components/pages/default/map.html.twig', [
            'positions' => $positions,
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
