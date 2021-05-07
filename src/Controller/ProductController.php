<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategorieRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="front_products")
     */
    public function front_products(CategorieRepository $categorieRepository): Response
    {

        return $this->render('components/pages/product/_categ_list.html.twig', [
            'categories' => $categorieRepository->findBy(['parent' => null ]),
        ]);
    }

    /**
     * @Route("/categorie/{id}", name="product_categorie_show", methods={"GET"})
     */
    public function showCategorie(CategorieRepository $categorieRepository, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $subCategories = $entityManager->getRepository(Categorie::class)->findBy(['parent' => $categorieRepository->find($id)]);
        $products = $entityManager->getRepository(Product::class)->findBy(['categorie' => $categorieRepository->find($id)]);
        //si subcategorie = 0 alors je redirige vers la route /categorie/{id}/items
        if ($subCategories === [] ) {
            return $this->redirectToRoute('product_categorie_items_show', [ 'id' => $id ]);            
        }

        return $this->render('components/pages/product/_subcateg_list.html.twig', [
            'categorie' => $categorieRepository->find($id),
            'title' => 'Les sous catégories de',
            'categories' => $subCategories,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/categorie/{id}/items", name="product_categorie_items_show", methods={"GET"})
     */
    public function showCategorieItems(CategorieRepository $categorieRepository, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $products = $entityManager->getRepository(Product::class)->findBy(['categorie' => $categorieRepository->find($id)]);

        return $this->render('components/pages/product/index_front.html.twig', [
            'categorie' => $categorieRepository->find($id),
            'title' => 'Voici les produits',
            'products' => $products,
        ]);
    }
    /**
     * @IsGranted("ROLE_PRODUCER")
     * @Route("/produits", name="product_index")
     */
    public function read_produits(ProductRepository $productRepository): Response
    {
        return $this->render('components/pages/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/produit", name="un_product", methods={"GET"})
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

        $form = $this->createForm(ProductType::class, $product);
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
     * @Route("/edit/{id}", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
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

            return $this->redirectToRoute('product_index');
        }

        return $this->render('components/pages/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/remove/{id}", name="product_delete", methods={"POST"})
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
