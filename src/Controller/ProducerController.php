<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Producer;
use App\Entity\Product;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\ProducerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/producer")
 */
class ProducerController
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

    /**
     * @var Security
     */
    private $security;

    public function __construct(Environment $twig, EntityManagerInterface $em, RouterInterface $router, FormFactoryInterface $formFactory, Security $security)
    {
        $this->twig = $twig;
        $this->em = $em;
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->security = $security;
    }

    /**
     * @Route("/", name="front_producteurs")
     */
    public function front_producteurs()
    {
        return new Response($this->twig->render('components/pages/front_producteurs/index.html.twig', [
            'producers' => $this->em->getRepository(Producer::class)->findAll(),
        ]));
    }

    /**
     * @Route("/produdu", name="front_produdu")
     */
    public function front_porodudu ()
    {
        return new Response($this->twig->render('components/pages/front_producteurs/produdu.html.twig', [
            'producers' => $this->em->getRepository(Producer::class)->findAll(),
        ]));
    }
    /**
     * @Route("/producteurs", name="producer_index", methods={"GET"})
     */
    public function index()
    {
        return new Response($this->twig->render('components/pages/producer/producer_list.html.twig', [
            'producers' => $this->em->getRepository(Producer::class)->findAll(),
        ]));
    }


    /**
     * @Route("/new", name="producer_new", methods={"GET","POST"})
     */
    public function new(Request $request)
    {
        $producer = new Producer();
        $form = $this->formFactory->create(ProducerType::class, $producer);
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
            
            $user = $this->em->getRepository(User::class)->find($this->security->getUser());
            $user->setRoles(["ROLE_PRODUCER"]);

            $producer->setUser($this->security->getUser());
            
            
            $this->em->persist($producer);
            $this->em->flush();

            return new RedirectResponse(
                $this->router->generate(
                    'homepage',
                )
            );
        }

        return new Response($this->twig->render('components/pages/producer/new.html.twig', [
            'producer' => $producer,
            'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/{id}", name="producer_show", methods={"GET","POST"})
     */
    public function show(Request $request, Producer $producer, int $id)
    {

        
        $producer = $this->em->getRepository(Producer::class)->find($id);
        $products = $this->em->getRepository(Product::class)->findBy(['producer' => $producer]);

        // formulaire pour les commentaire
        $comment = new Comment(); 
        $form = $this->formFactory->create(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $comment->setUser($this->security->getUser());
            $comment->setProducer($producer);
            $this->em->persist($comment);
            $this->em->flush();

            return new RedirectResponse(
                $this->router->generate(
                    'producer_show',
                    ['id' => $id]
                )
            );
        }

        return new Response($this->twig->render('components/pages/producer/show.html.twig', [


            'producer' => $producer,
            'products' => $products,
            'comments' => $this->em->getRepository(Comment::class)->findBy(['producer'=> $producer->getId()]),
            'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/{id}/edit", name="producer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Producer $producer)
    {
        $form = $this->formFactory->create(ProducerType::class, $producer);
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
                    'producer_index',
                )
            );
        }

        return new Response($this->twig->render('components/pages/producer/edit.html.twig', [
            'producer' => $producer,
            'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/{id}", name="producer_delete", methods={"POST"})
     */
    public function delete(Request $request, Producer $producer)
    {
        if ($this->isCsrfTokenValid('delete'.$producer->getId(), $request->request->get('_token'))) {
            
            
            $this->em->remove($producer);
            $this->em->flush();
        }

        return new RedirectResponse(
            $this->router->generate(
                'producer_index',
            )
        );
    }

}
