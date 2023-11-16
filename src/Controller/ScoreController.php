<?php

namespace App\Controller;

use App\Entity\Score;
use App\Dto\SearchDto;
use App\Form\ScoreType;
use App\Form\SearchType;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/score')]
class ScoreController extends AbstractController
{
    #[Route('/', name: 'app_score_index', methods: ['GET', 'POST'])]
    public function index(Request $request, SubjectRepository $subjectRepository): Response
    {
        $searchDto = new SearchDto();
        $searchForm = $this->createForm(SearchType::class, $searchDto);
        $searchForm->handleRequest($request);

        return $this->render('score/index.html.twig', [
            'searchForm' => $searchForm,
            'searchValue' => $searchDto->search,
            'subjects' => $subjectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_score_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $score = new Score();
        $form = $this->createForm(ScoreType::class, $score);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($score);
            $entityManager->flush();

            return $this->redirectToRoute('app_score_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('score/new.html.twig', [
            'score' => $score,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_score_show', methods: ['GET'])]
    public function show(Score $score): Response
    {
        return $this->render('score/show.html.twig', [
            'score' => $score,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_score_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Score $score, EntityManagerInterface $entityManager): Response
    {
        $originalSupplements = new ArrayCollection();

        // Create an ArrayCollection of the current Supplement objects in the database
        foreach ($score->getSupplements() as $supplement) {
            $originalSupplements->add($supplement);
        }

        $form = $this->createForm(ScoreType::class, $score);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalSupplements as $supplement) {              
                if (false === $score->getSupplements()->contains($supplement)) {
                    $supplement->getScores()->removeElement($score);
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('score/edit.html.twig', [
            'score' => $score,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_score_delete', methods: ['POST'])]
    public function delete(Request $request, Score $score, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$score->getId(), $request->request->get('_token'))) {
            $entityManager->remove($score);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_score_index', [], Response::HTTP_SEE_OTHER);
    }
}
