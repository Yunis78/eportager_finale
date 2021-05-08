<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategorieRepository;
use App\Repository\ProductRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/product")
 */
class ProductController
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
     * @Route("/", name="front_products")
     */
    public function front_products(CategorieRepository $categorieRepository)
    {

        return new Response($this->twig->render('components/pages/product/_categ_list.html.twig', [
            'categories' => $categorieRepository->findBy(['parent' => null ]),
        ]));
    }

    /**
     * @Route("/categorie/{id}", name="product_categorie_show", methods={"GET"})
     */
    public function showCategorie(int $id)
    {
        
        $categorie = $this->em->getRepository(Categorie::class)->find($id);
        $subCategories = $this->em->getRepository(Categorie::class)->findBy(['parent' => $categorie]);
        $products = $this->em->getRepository(Product::class)->findBy(['categorie' => $categorie]);
        
        if ($subCategories === [] ) {
            return new RedirectResponse(
                $this->router->generate(
                    'product_categorie_items_show',
                    [ 'id' => $id ]
                )
            );
        }

        return new Response($this->twig->render('components/pages/product/_subcateg_list.html.twig', [
            'categorie' => $categorie,
            'title' => 'Les sous catégories de',
            'categories' => $subCategories,
            'products' => $products,
        ]));
    }

    /**
     * @Route("/categorie/{id}/items", name="product_categorie_items_show", methods={"GET"})
     */
    public function showCategorieItems(CategorieRepository $categorieRepository, int $id)
    {
        
        $categorie = $this->em->getRepository(Categorie::class)->find($id);
        $products = $this->em->getRepository(Product::class)->findBy(['categorie' => $categorie]);

        return new Response($this->twig->render('components/pages/product/index_front.html.twig', [
            'categorie' => $categorie,
            'title' => 'Voici les produits',
            'products' => $products,
        ]));
    }

    /**
     * @IsGranted("ROLE_PRODUCER")
     * @Route("/produits", name="product_index")
     */
    public function read_produits(ProductRepository $productRepository)
    {
        return new Response($this->twig->render('components/pages/product/index.html.twig', [
            'products' => $this->em->getRepository(Product::class)->findAll(),
        ]));
    }

    /**
     * @IsGranted("ROLE_PRODUCER")
     * 
     * @Route("/new" , name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request)
    {
        $product = new Product();

        $user = $this->security->getUser();

        $form = $this->formFactory->create(ProductType::class, $product);
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
            
            $product->setProducer($user->getProducer());

            
            $this->em->persist($product);
            $this->em->flush();

            return new RedirectResponse(
                $this->router->generate(
                    'product_index',
                )
            );
        }

        return new Response($this->twig->render('components/pages/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product)
    {
        return new Response($this->twig->render('components/pages/product/show.html.twig', [
            'product' => $product,
        ]));
    }

    /**
     * @Route("/edit/{id}", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product)
    {
        $form = $this->formFactory->create(ProductType::class, $product);
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
                    'product_index',
                )
            );
        }

        return new Response($this->twig->render('components/pages/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/remove/{id}", name="product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product)
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            
            $this->em->remove($product);
            $this->em->flush();
        }

        return new RedirectResponse(
            $this->router->generate(
                'product_index',
            )
        );
    }

    
}
