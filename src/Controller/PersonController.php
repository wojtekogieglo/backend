<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\Filter\PersonFilterType;
use App\Form\Type\PersonType;
use App\Repository\PersonRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/person")
 */
class PersonController extends AbstractController
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
     * @Route("/", name="person_index", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $form = $this->createForm(PersonFilterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $people = $this->personRepository->findByFilters($form->getData());
        } else {
            $people = $this->personRepository->findAll();
        }

        return $this->render('person/index.html.twig', [
            'people' => $people,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create", name="person_create", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $person = new Person();

        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();

            $this->entityManager->persist($person);
            $this->entityManager->flush();

            $this->addFlash('success', 'Dodano nową osobę');

            return $this->redirectToRoute('person_index');
        }

        return $this->render('person/create.html.twig', [
            'person' => $person,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="person_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Person $person
     * @return Response
     */
    public function editAction(Request $request, Person $person): Response
    {
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($person);
            $this->entityManager->flush();

            $this->addFlash('success', 'Pomyślnie edytowano osobę');

            return $this->redirectToRoute('person_index');
        }

        return $this->render('person/edit.html.twig', [
            'person' => $person,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/delete", name="person_delete", methods={"GET"})
     * @param Person $person
     * @return Response
     */
    public function softDeleteAction(Person $person): Response
    {
        $person->setState(Person::STATE_DELETED);
        $this->entityManager->persist($person);
        $this->entityManager->flush();

        $this->addFlash('success', 'Osoba została usunięta');

        return $this->redirectToRoute('person_index');
    }

    /**
     * @Route("/{id}/active", name="person_active", methods={"GET"})
     * @param Person $person
     * @return Response
     */
    public function activeAction(Person $person): Response
    {
        $person->setState(Person::STATE_ACTIVE);
        $this->entityManager->persist($person);
        $this->entityManager->flush();

        $this->addFlash('success', 'Osoba została aktywowana');

        return $this->redirectToRoute('person_index');
    }
}
