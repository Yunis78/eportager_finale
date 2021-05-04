<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontProducteursController extends AbstractController
{
    /**
     * @Route("/producteurs", name="front_producteurs")
     */
    public function index(): Response
    {
        return $this->render('components/pages/front_producteurs/index.html.twig', [
            'controller_name' => 'FrontProducteursController',
        ]);
    }
}
