<?php

namespace App\Controller;

use App\Entity\CommandeProduct;
use App\Form\CommandeProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @Route("/commande_product")
 */
class CommandeProductController
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
     * @Route("/", name="commande_product_index", methods={"GET"})
     */
    public function index()
    {
        return new Response($this->twig->render('components/pages/commande_product/index.html.twig', [
            'commande_products' => $this->em->getRepository(CommandeProduct::class)->findAll(),
        ]));
    }

    /**
     * @Route("/new", name="commande_product_new", methods={"GET","POST"})
     */
    public function new(Request $request)
    {
        $commandeProduct = new CommandeProduct();
        $form = $this->formFactory->create(CommandeProductType::class, $commandeProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->em->persist($commandeProduct);
            $this->em->flush();

            return new RedirectResponse(
                $this->router->generate(
                    'commande_product_index',
                )
            );
        }

        return new Response($this->twig->render('components/pages/commande_product/new.html.twig', [
            'commande_product' => $commandeProduct,
            'form' => $form->createView(),
        ]));
    }

    // /**
    //  * @Route("/{id}", name="commande_product_show", methods={"GET"})
    //  */
    // public function show(Request $request, int $id , CommandeProduct $commandeProduct)
    // {
    //     return new Response($this->twig->render('components/pages/commande_product/show.html.twig', [
    //         'commande_product' => $commandeProduct,
    //     ]));
    // }

    /**
     * @Route("/{id}/edit", name="commande_product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CommandeProduct $commandeProduct)
    {
        $form = $this->formFactory->create(CommandeProductType::class, $commandeProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return new RedirectResponse(
                $this->router->generate(
                    'commande_product_index',
                )
            );
        }

        return new Response($this->twig->render('components/pages/commande_product/edit.html.twig', [
            'commande_product' => $commandeProduct,
            'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/{id}", name="commande_product_delete", methods={"POST"})
     */
    public function delete(Request $request, CommandeProduct $commandeProduct)
    {
        if ($this->isCsrfTokenValid('delete'.$commandeProduct->getId(), $request->request->get('_token'))) {
            
            $this->em->remove($commandeProduct);
            $this->em->flush();
        }

        return new RedirectResponse(
            $this->router->generate(
                'commande_product_index',
            )
        );
    }
}
