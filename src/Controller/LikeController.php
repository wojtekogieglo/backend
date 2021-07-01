<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\PersonInterface;
use App\Entity\Product;
use App\Entity\ProductInterface;
use App\Form\Type\PersonLikeProductType;
use App\Repository\PersonRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/like")
 */
class LikeController extends AbstractController
{
    private PersonRepositoryInterface $personRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        PersonRepositoryInterface $personRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->personRepository = $personRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="like_index", methods={"GET"})
     * @return Response
     */
    public function indexAction(): Response
    {
        $people = $this->personRepository->findAll();

        return $this->render('like/index.html.twig', [
            'people' => $people,
        ]);
    }

    /**
     * @Route("/create", name="like_create", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $form = $this->createForm(PersonLikeProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $like = $form->getData();

            /** @var PersonInterface $person */
            $person = $like['person'];

            /** @var ProductInterface $product */
            $product = $like['product'];

            if ($person->hasProduct($product)) {
                $this->addFlash('danger', 'Wybrana osoba już lubi ten produkt!');
                return $this->redirectToRoute('like_create');
            }

            $person->addProduct($product);

            $this->entityManager->persist($person);
            $this->entityManager->flush();

            $this->addFlash('success', 'Dane zostały zapisane');

            return $this->redirectToRoute('like_index');
        }

        return $this->render('like/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{person_id}/{product_id}/edit", name="like_edit", methods={"GET","POST"})
     * @ParamConverter("person", options={"mapping": {"person_id" : "id"}})
     * @ParamConverter("product", options={"mapping": {"product_id" : "id"}})
     * @param Request $request
     * @param Person $person
     * @param Product $product
     * @return Response
     */
    public function editAction(Request $request, Person $person, Product $product): Response
    {
        if (!$person->hasProduct($product)) {
            throw $this->createNotFoundException('The like does not exist!');
        }

        $form = $this->createForm(PersonLikeProductType::class);
        $form->setData([
            'person' => $person,
            'product' => $product
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $like = $form->getData();

            /** @var PersonInterface $person */
            $formPerson = $like['person'];

            /** @var ProductInterface $product */
            $formProduct = $like['product'];

            if ($formPerson == $person && $formProduct == $product) {
                return $this->handlePersonAlreadyLikeProductEdit($person, $product);
            } elseif ($formPerson != $person && $formProduct != $product) {
                if ($formPerson->hasProduct($formProduct)) {
                    return $this->handlePersonAlreadyLikeProductEdit($person, $product);
                }
                $product->removePerson($person);
                $formProduct->addPerson($person);

                $this->entityManager->persist($product);
                $this->entityManager->persist($formProduct);
                $this->entityManager->flush();
            } elseif ($formPerson != $person) {
                if ($formPerson->hasProduct($product)) {
                    return $this->handlePersonAlreadyLikeProductEdit($person, $product);
                }

                $product->removePerson($person);
                $product->addPerson($formPerson);

                $this->entityManager->persist($product);
                $this->entityManager->flush();
            } elseif ($formProduct != $product) {
                if ($formProduct->hasPerson($person)) {
                    return $this->handlePersonAlreadyLikeProductEdit($person, $product);
                }

                $person->removeProduct($product);
                $person->addProduct($formProduct);

                $this->entityManager->persist($product);
                $this->entityManager->flush();
            }

            $this->addFlash('success', 'Dane zostały zapisane');

            return $this->redirectToRoute('like_index');
        }

        return $this->render('like/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{person_id}/{product_id}/delete", name="like_delete", methods={"DELETE"})
     * @ParamConverter("person", options={"mapping": {"person_id" : "id"}})
     * @ParamConverter("product", options={"mapping": {"product_id" : "id"}})
     * @param Person $person
     * @param Product $product
     * @return Response
     */
    public function deleteAction(Person $person, Product $product): Response
    {
        $person->removeProduct($product);
        $this->entityManager->persist($person);
        $this->entityManager->flush();

        $this->addFlash('success', 'Dane zostały zapisane');

        return new Response();
    }

    private function handlePersonAlreadyLikeProductEdit(Person $person, Product $product)
    {
        $this->addFlash('danger', 'Wybrana osoba już lubi ten produkt!');

        return $this->redirectToRoute(
            'like_edit',
            ['person_id' => $person->getId(), 'product_id' => $product->getId()]
        );
    }
}
