<?php

namespace App\Controller;

use App\Entity\CommandeProduct;
use App\Form\CommandeProductType;
use App\Repository\CommandeProductRepository;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/commande/product")
 */
class CommandeProductController extends AbstractController
{
    /**
     * @Route("/", name="commande_product_index", methods={"GET"})
     */
    public function index(CommandeProductRepository $commandeProductRepository): Response
    {
        return $this->render('components/pages/commande_product/index.html.twig', [
            'commande_products' => $commandeProductRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="commande_product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commandeProduct = new CommandeProduct();
        $form = $this->createForm(CommandeProductType::class, $commandeProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commandeProduct);
            $entityManager->flush();

            return $this->redirectToRoute('commande_product_index');
        }

        return $this->render('components/pages/commande_product/new.html.twig', [
            'commande_product' => $commandeProduct,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commande_product_show", methods={"GET"})
     */
    public function show(CommandeProduct $commandeProduct): Response
    {
        return $this->render('components/pages/commande_product/show.html.twig', [
            'commande_product' => $commandeProduct,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commande_product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CommandeProduct $commandeProduct): Response
    {
        $form = $this->createForm(CommandeProductType::class, $commandeProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commande_product_index');
        }

        return $this->render('components/pages/commande_product/edit.html.twig', [
            'commande_product' => $commandeProduct,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commande_product_delete", methods={"POST"})
     */
    public function delete(Request $request, CommandeProduct $commandeProduct): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commandeProduct->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commandeProduct);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande_product_index');
    }
    /**
     * @Route("/panier", name="cart_index")
     */
    public function panier(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal()
        ]);
    }

    /**
     * @Route("/panier/add/{id}", name="cart_add")
     */
    public function add($id, CartService $cartService) {
        
        $cartService->add($id);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/panier/remove/{id}", name="cart_remove")
     */
    public function remove($id, CartService $cartService) {

        $cartService->remove($id);

        return $this->redirectToRoute("cart_index");
    }
}
