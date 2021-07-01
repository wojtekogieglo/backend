<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Filter\ProductFilterType;
use App\Form\Type\ProductType;
use App\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    private ProductRepositoryInterface $productRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="product_index", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $form = $this->createForm(ProductFilterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $products = $this->productRepository->findByFilters($form->getData());
        } else {
            $products = $this->productRepository->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create", name="product_create", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $this->addFlash('success', 'Dodano nowy produkt');

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/create.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function editAction(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType


        ::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $this->addFlash('success', 'Pomyślnie edytowano produkt');

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/delete", name="product_delete", methods={"DELETE"})
     * @param Product $product
     * @return Response
     */
    public function deleteAction(Product $product): Response
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        $this->addFlash('success', 'Produkt został usunięty');

        return new Response();
    }
}
