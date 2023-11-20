<?php

namespace App\Controller;

use App\Dto\SearchDto;
use App\Form\SearchType;
use App\Repository\StudentRepository;
use App\Repository\SubjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(
        Request $request, 
        StudentRepository $studentRepository, 
        SubjectRepository $subjectRepository
    ): Response {
        $data = [];        
        $subjects = $subjectRepository->findAll();
        $students = $studentRepository->findAll();

        $searchDto = new SearchDto();
        $searchForm = $this->createForm(SearchType::class, $searchDto);
        $searchForm->handleRequest($request);

        foreach ($students as $student) {
            if (true === $student->inSearch($searchDto->search)) {
                $case['student'] = $student;
                $case['score'] = $studentRepository->globalScore($student);
                foreach ($subjects as $subject) {
                    $case['subjects'][$subject->getLibelle()] = [
                        'score' => $studentRepository->scoreByStudentAndSubject($student, $subject),
                    ];
                }
                $data[] = $case;
            }
        }
        
        return $this->render('home/index.html.twig', [
            'searchForm' => $searchForm,
            'data' => $data,
        ]);
    }

    #[Route('/schema', name: 'app_home_schema', methods: ['GET'])]
    public function schema(): Response
    {
        return $this->render('home/schema.html.twig');
    }
}
