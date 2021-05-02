<?php

namespace App\Controller;

use App\Entity\File;
use App\Form\FileType;
use App\Repository\FileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/file")
 */
class FileController extends AbstractController
{
    /**
     * @Route("/", name="file_index", methods={"GET"})
     */
    public function index(FileRepository $fileRepository): Response
    {
        return $this->render('components/pages/file/index.html.twig', [
            'files' => $fileRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="file_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $file = new File();
        $form = $this->createForm(FileType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

             // Get file field
             $uploaded_file = $form->get('file')->getData();

             // If has file
             if ($uploaded_file)
             {
                 // File Content
                $file_content = file_get_contents($uploaded_file->getPathname());
 
                 // Generate MD5 from file content
                $file_md5 = md5($file_content);
 
                 // Get file extension
                $file_extension = $uploaded_file->guessExtension();
 
                $original_name = $uploaded_file->getClientOriginalName();
                $original_name_array = explode('.', $original_name);
 
                $file_extension = end($original_name_array);
 
                 // Generate new file name
                $new_file = $file_md5.".".$file_extension;
 
                 // Move file
                $uploaded_file->move(
                    "./upload/",
                    $new_file
                );
 
                 // Save the new file name in the "path" field
                $file->setPath( $new_file );
             }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($file);
            $entityManager->flush();

            return $this->redirectToRoute('file_index');
        }

        return $this->render('components/pages/file/new.html.twig', [
            'file' => $file,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="file_show", methods={"GET"})
     */
    public function show(File $file): Response
    {
        return $this->render('components/pages/file/show.html.twig', [
            'file' => $file,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="file_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, File $file): Response
    {
        $form = $this->createForm(FileType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('file_index');
        }

        return $this->render('components/pages/file/edit.html.twig', [
            'file' => $file,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="file_delete", methods={"POST"})
     */
    public function delete(Request $request, File $file): Response
    {
        if ($this->isCsrfTokenValid('delete'.$file->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($file);
            $entityManager->flush();
        }

        return $this->redirectToRoute('file_index');
    }
}
