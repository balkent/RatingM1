<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\StudentRepository;
use App\Repository\SubjectRepository;
use App\Repository\ExerciseRepository;
use App\Repository\SupplementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        StudentRepository $studentRepository, 
        SubjectRepository $subjectRepository
    ): Response {
        $data = [];        
        $subjects = $subjectRepository->findAll();
        $students = $studentRepository->findAll();

        foreach ($students as $student) {
            $case['student'] = $student;
            $case['score'] = $studentRepository->globalScore($student);
            foreach ($subjects as $subject) {
                $case['subjects'][$subject->getLibelle()] = [
                    'score' => $studentRepository->scoreByStudentAndSubject($student, $subject),
                ];
            }
            $data[] = $case;
        }
        
        return $this->render('home/index.html.twig', [
            'data' => $data,
        ]);
    }

    #[Route('/{id}/pdf', name: 'app_home_generate_pdf', methods: ['GET'])]
    public function generatePdf(
        Student $student, 
        StudentRepository $studentRepository, 
        SupplementRepository $supplementRepository,
        SubjectRepository $subjectRepository,
        ExerciseRepository $exerciseRepository
    ): Response {
        $data = [];        
        $subjects = $subjectRepository->findAll();

        $data['student'] = $student;
        $data['score'] = $studentRepository->globalScore($student);
        foreach ($subjects as $subject) {
            $data['subjects'][$subject->getLibelle()] = [
                'score' => $studentRepository->scoreByStudentAndSubject($student, $subject),
                'supplements' => $supplementRepository->findByStudentAndSubject($student, $subject),
                'exos' => $exerciseRepository->findBy(['subject' => $subject]),
            ];
        }

        return $this->render('home/show.html.twig', [
            'data' => $data,
        ]);
    }

    #[Route('/schema', name: 'app_home_schema', methods: ['GET'])]
    public function schema(): Response
    {
        return $this->render('home/schema.html.twig');
    }

    // #[Route('/{id}/pdf', name: 'app_student_generate_pdf', methods: ['GET'])]
    // public function generatePdf(Student $student): Response
    // {
    //     return $this->render('student/show.html.twig', [
    //         'student' => $student,
    //     ]);
    // }
}
