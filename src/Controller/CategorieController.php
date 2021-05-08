<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Media;
use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class CategorieController.
 * 
 * @Route("/categorie")
 */
class CategorieController
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
     * @Route("/", name="categorie_index")
     */
    public function index()
    {
        return new Response($this->twig->render('components/pages/categorie/index.html.twig', [
            'categories' => $this->em->getRepository(Categorie::class)->findAll(),
        ]));
    }

    /**
     * @Route("/new", name="categorie_new", methods={"GET","POST"})
     */
    public function new(Request $request)
    {
        $categorie = new Categorie();


        $form = $this->formFactory->create(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            foreach($form->get('file') as $media)
            {
                // Get file field
                $uploaded_file = $media->get('file')->getData();

                // If has file
                if ($uploaded_file)
                {
                    // File Content
                    $file_content = file_get_contents($uploaded_file->getPathname());

                    // Generate MD5 from file content
                    $file_md5 = md5($file_content);

                    // Get file extension
                    $file_extension = $uploaded_file->guessExtension();

                    // Generate new file name
                    $new_file = $file_md5.".".$file_extension;

                    // Move file
                    $uploaded_file->move(
                        "./upload/categorie/",
                        $new_file
                    );

                    // Save the new file name in the "path" field
                    $media->getData()->setPath( $new_file );
                }
            }
            
            $this->em->persist($categorie);
            $this->em->flush();

            return new RedirectResponse(
                $this->router->generate(
                    'categorie_new',
                )
            );
        }

        return new Response($this->twig->render('components/pages/categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/{id}", name="categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie)
    {
        return $this->twig->render('components/pages/categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categorie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categorie $categorie)
    {
        $form = $this->formFactory->create(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($form->get('file') as $media)
            {
                // Get file field
                $uploaded_file = $media->get('file')->getData();

                // If has file
                if ($uploaded_file)
                {
                    // File Content
                    $file_content = file_get_contents($uploaded_file->getPathname());

                    // Generate MD5 from file content
                    $file_md5 = md5($file_content);

                    // Get file extension
                    $file_extension = $uploaded_file->guessExtension();

                    // Generate new file name
                    $new_file = $file_md5.".".$file_extension;

                    // Move file
                    $uploaded_file->move(
                        "./upload/",
                        $new_file
                    );

                    // Save the new file name in the "path" field
                    $media->getData()->setPath( $new_file );
                }
            }

            $this->em->flush();

            return new RedirectResponse(
                $this->router->generate(
                    'categorie_index',
                )
            );
        }

        return new Response($this->twig->render('components/pages/categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/{id}", name="categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie)
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {

            
            $this->em->remove($categorie);
            $this->em->flush();
        }
        return new RedirectResponse(
            $this->router->generate(
                'categorie_index',
            )
        );
    }
}
