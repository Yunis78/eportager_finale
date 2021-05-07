<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Producer;
use App\Entity\Product;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\ProducerType;
use App\Repository\ProducerRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/producer")
 */
class ProducerController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct( EntityManagerInterface $em )
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="front_producteurs")
     */
    public function front_producteurs(ProducerRepository $producerRepository): Response
    {
        return $this->render('components/pages/front_producteurs/index.html.twig', [
            'producers' => $producerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/produdu", name="front_produdu")
     */
    public function front_porodudu (ProducerRepository $producerRepository): Response
    {
        return $this->render('components/pages/front_producteurs/produdu.html.twig', [
            'producers' => $producerRepository->findAll(),
        ]);
    }
    /**
     * @Route("/producteurs", name="producer_index", methods={"GET"})
     */
    public function index(ProducerRepository $producerRepository): Response
    {
        return $this->render('components/pages/producer/producer_list.html.twig', [
            'producers' => $producerRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="producer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $producer = new Producer();
        $form = $this->createForm(ProducerType::class, $producer);
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
            
            $user = $this->em->getRepository(User::class)->find($this->getUser());
            $user->setRoles(["ROLE_PRODUCER"]);

            $producer->setUser($this->getUser());
            
            $this->em = $this->getDoctrine()->getManager();
            $this->em->persist($producer);
            $this->em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('components/pages/producer/new.html.twig', [
            'producer' => $producer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="producer_show", methods={"GET","POST"})
     */
    public function show(Request $request, Producer $producer, int $id): Response
    {

        
        $producer = $this->em->getRepository(Producer::class)->find($id);
        $products = $this->em->getRepository(Product::class)->findBy(['producer' => $producer]);

        // formulaire pour les commentaire
        $comment = new Comment(); 
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $comment->setUser($this->getUser());
            $comment->setProducer($producer);
            $this->em->persist($comment);
            $this->em->flush();

            return $this->redirectToRoute('producer_show', ['id' => $id]);
        }

        return $this->render('components/pages/producer/show.html.twig', [


            'producer' => $producer,
            'products' => $products,
            'comments' => $this->em->getRepository(Comment::class)->findBy(['producer'=> $producer->getId()]),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="producer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Producer $producer): Response
    {
        $form = $this->createForm(ProducerType::class, $producer);
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

            return $this->redirectToRoute('producer_index');
        }

        return $this->render('components/pages/producer/edit.html.twig', [
            'producer' => $producer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="producer_delete", methods={"POST"})
     */
    public function delete(Request $request, Producer $producer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$producer->getId(), $request->request->get('_token'))) {
            
            $this->em = $this->getDoctrine()->getManager();
            $this->em->remove($producer);
            $this->em->flush();
        }

        return $this->redirectToRoute('producer_index');
    }

}
