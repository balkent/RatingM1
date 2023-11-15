<?php

namespace App\Controller;

use App\Entity\Supplement;
use App\Form\SupplementType;
use App\Dto\SupplementSearchDto;
use App\Form\SupplementSearchType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SupplementTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/supplement')]
class SupplementController extends AbstractController
{
    #[Route('/', name: 'app_supplement_index', methods: ['GET', 'POST'])]
    public function index(Request $request, SupplementTypeRepository $supplementTypeRepository): Response
    {
        $supplementSearchDto = new SupplementSearchDto();
        $searchForm = $this->createForm(SupplementSearchType::class, $supplementSearchDto);
        $searchForm->handleRequest($request);

        return $this->render('supplement/index.html.twig', [
            'searchForm' => $searchForm,
            'searchValue' => $supplementSearchDto->search,
            'supplementTypes' => $supplementTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_supplement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $supplement = new Supplement();
        $form = $this->createForm(SupplementType::class, $supplement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($supplement);
            $entityManager->flush();

            return $this->redirectToRoute('app_supplement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supplement/new.html.twig', [
            'supplement' => $supplement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_supplement_show', methods: ['GET'])]
    public function show(Supplement $supplement): Response
    {
        return $this->render('supplement/show.html.twig', [
            'supplement' => $supplement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_supplement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Supplement $supplement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SupplementType::class, $supplement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_supplement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supplement/edit.html.twig', [
            'supplement' => $supplement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_supplement_delete', methods: ['POST'])]
    public function delete(Request $request, Supplement $supplement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$supplement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($supplement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_supplement_index', [], Response::HTTP_SEE_OTHER);
    }
}
