<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Media;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="categorie_index")
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('components/pages/categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
        
    }

    /**
     * @Route("/cat", name="cat")
     */
    public function categorie(): Response
    {
        return $this->render('components/pages/product/categorie.html.twig', [
            'controller_name' => 'ProduitsController',
        ]);
    }

    /**
     * @Route("/new", name="categorie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categorie = new Categorie();


        $form = $this->createForm(CategorieType::class, $categorie);
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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_new');
        }

        return $this->render('components/pages/categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('components/pages/categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categorie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categorie $categorie): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
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

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('components/pages/categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index');
    }
}
