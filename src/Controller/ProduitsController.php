<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitsController extends AbstractController
{
    /**
     * @Route("/produits", name="produits")
     */
    public function index(): Response
    {
        return $this->render('produits/index.html.twig', [
            'controller_name' => 'ProduitsController',
        ]);
    }

    /**
     * @Route("/categorie", name="categorie")
     */
    public function categorie(): Response
    {
        return $this->render('produits/categorie.html.twig', [
            'controller_name' => 'ProduitsController',
        ]);
    }

        /**
     * @Route("/produit", name="produit")
     */
    public function produit(): Response
    {
        return $this->render('produits/produit.html.twig', [
            'controller_name' => 'ProduitsController',
        ]);
    }
}
