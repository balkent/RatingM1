<?php

namespace App\Controller;

use App\Entity\SupplementType;
use App\Form\SupplementTypeType;
use App\Repository\SupplementTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/supplement_type')]
class SupplementTypeController extends AbstractController
{
    #[Route('/', name: 'app_supplement_type_index', methods: ['GET'])]
    public function index(SupplementTypeRepository $supplementTypeRepository): Response
    {
        return $this->render('supplement_type/index.html.twig', [
            'supplement_types' => $supplementTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_supplement_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $supplementType = new SupplementType();
        $form = $this->createForm(SupplementTypeType::class, $supplementType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($supplementType);
            $entityManager->flush();

            return $this->redirectToRoute('app_supplement_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supplement_type/new.html.twig', [
            'supplement_type' => $supplementType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_supplement_type_show', methods: ['GET'])]
    public function show(SupplementType $supplementType): Response
    {
        return $this->render('supplement_type/show.html.twig', [
            'supplement_type' => $supplementType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_supplement_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SupplementType $supplementType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SupplementTypeType::class, $supplementType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_supplement_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supplement_type/edit.html.twig', [
            'supplement_type' => $supplementType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_supplement_type_delete', methods: ['POST'])]
    public function delete(Request $request, SupplementType $supplementType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$supplementType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($supplementType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_supplement_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
