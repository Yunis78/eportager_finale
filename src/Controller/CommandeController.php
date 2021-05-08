<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig\Environment;

/**
 * @Route("/commande")
 */
class CommandeController
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
     * @Route("/", name="commande_index", methods={"GET"})
     */
    public function index()
    {
        return new Response($this->twig->render('components/pages/commande/index.html.twig', [
            'commandes' => $this->em->getRepository(Commande::class)->findAll(),
        ]));
    }

    /**
     * @Route("/new", name="commande_new", methods={"GET","POST"})
     */
    public function new(Request $request)
    {
        $commande = new Commande();
        $form = $this->formFactory->create(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($commande);
            $this->em->flush();

            return new RedirectResponse(
                $this->router->generate(
                    'commande_index',
                )
            );
        }

        return new Response($this->twig->render('components/pages/commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/{id}", name="commande_show", methods={"GET"})
     */
    public function show(Commande $commande)
    {
        return new Response($this->twig->render('components/pages/commande/show.html.twig', [
            'commande' => $commande,
        ]));
    }

    /**
     * @Route("/{id}/edit", name="commande_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Commande $commande)
    {
        $form = $this->formFactory->create(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return new RedirectResponse(
                $this->router->generate(
                    'commande_index',
                )
            );
        }

        return new Response($this->twig->render('components/pages/commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/{id}", name="commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commande $commande)
    {
        if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->request->get('_token'))) {

            $this->em->remove($commande);
            $this->em->flush();
        }

        return new RedirectResponse(
            $this->router->generate(
                'commande_index',
            )
        );
    }
}
