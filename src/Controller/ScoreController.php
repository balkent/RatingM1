<?php

namespace App\Controller;

use App\Dto\SearchDto;
use App\Entity\Score;
use App\Entity\Student;
use App\Form\ScoreType;
use App\Form\SearchType;
use App\Generator\HtmlGenerator;
use App\Repository\StudentRepository;
use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/generate/all', name: 'app_score_generate_all', methods: ['GET'])]
    public function generateAll(
        StudentRepository $studentRepository, 
        HtmlGenerator $htmlGenerator,
        ParameterBagInterface $parameterBag
    ): Response {
        $students = $studentRepository->findAll();
        $filesystem = new Filesystem();

        foreach ($students as $student) {
            $filesystem->dumpFile($parameterBag->get('srore_dir_absolute').'/'.$this->getNameFile($student), $htmlGenerator->generate($student));
        }

        $this->addFlash(
            'success',
            'Tout les notes des étudiants ont été générer'
        );

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/generate/{id}', name: 'app_score_generate_one', methods: ['GET'])]
    public function generate(
        Student $student,
        HtmlGenerator $htmlGenerator
    ): Response {
        return new Response($htmlGenerator->generate($student), 200, [
            'Content-Type' => 'text/html',
            'Content-disposition' => 'attachment; filename='.$this->getNameFile($student),
        ]);
    }

    private function getNameFile(Student $student) :string
    {
        return str_replace(' ', '_', strtolower($student->getLastName()).' '.strtolower($student->getName()).'.html');
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
