<?php

namespace App\Controller;
use App\Entity\File;
use App\Entity\Producer;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use App\Repository\ProducerRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
//use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

     /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(Security $security, EntityManagerInterface $em )
    {
        $this->security = $security;
        $this->em = $em;
    }
    /**
     * @Route("/", name="produits")
     */
    public function produits(): Response
    {
        return $this->render('components/pages/product/type.html.twig', [
            'controller_name' => 'ProduitsController',
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
     * @Route("/produits", name="product_produit", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('components/pages/product/produit.html.twig', [
            'products' => $productRepository->findAll(),
        ]);

        
    }

    /**
     * @IsGranted("ROLE_PRODUCER")
     * 
     * @Route("/new" , name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();

        $user = $this->getUser();

        // $user = $this->em->getRepository(User::class)->find($this->security->getUser()->getId());
        if (null === $user->getProducer() ) { 

            return $this->redirectToRoute('app_login');

        }
        
        // $product->setProducer($this->em->getRepository(Producer::class)->find($user));
        // $product->setProducer($user);

        // dd($product);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            dd($form->getData());

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


            $product->setProducer($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('components/pages/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('components/pages/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('components/pages/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
    
}
