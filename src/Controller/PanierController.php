<?php

namespace App\Controller;

use App\Service\Cart\CartService;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormFactoryInterface;

class PanierController
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
     * @Route("/panier", name="cart_index")
     */
    public function panier(CartService $cartService)
    {
        return new Response($this->twig->render('components/pages/panier/index.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal()
        ]));
    }

    /**
     * @Route("/panier/add/{id}", name="cart_add")
     */
    public function add($id, CartService $cartService) {
        
        $cartService->add($id);

        return new RedirectResponse(
            $this->router->generate(
                'cart_index',
            )
        );
    }

    /**
     * @Route("/panier/remove/{id}", name="cart_remove")
     */
    public function remove($id, CartService $cartService) {

        $cartService->remove($id);

        return new RedirectResponse(
            $this->router->generate(
                'cart_index',
            )
        );
    }
}
