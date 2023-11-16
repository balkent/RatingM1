<?php

namespace App\Controller;

use App\Entity\Student;
use App\Service\FileUploader;
use App\Repository\StudentRepository;
use App\Repository\SubjectRepository;
use App\Repository\ExerciseRepository;
use App\Repository\SupplementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class HtmlGeneratorController extends AbstractController
{
    #[Route('/html/generator/{id}', name: 'app_html_generator', methods: ['GET'])]
    public function index(
        Student $student, 
        StudentRepository $studentRepository, 
        SupplementRepository $supplementRepository,
        SubjectRepository $subjectRepository,
        ExerciseRepository $exerciseRepository,
        FileUploader $fileUploader,
        ParameterBagInterface $parameterBag
    ): Response {
        $data = [];        
        $subjects = $subjectRepository->findAll();

        $data['student'] = $student;
        $data['score'] = $studentRepository->globalScore($student);
        foreach ($subjects as $subject) {
            $exos = $exerciseRepository->findBy(['subject' => $subject]);
            foreach ($exos as $exo) {
                if (null !== $picture = $exo->getPicture()) {
                    $exo->setPicture($fileUploader->imageToBase64($picture));
                }
            }
            $data['subjects'][$subject->getLibelle()] = [
                'score' => $studentRepository->scoreByStudentAndSubject($student, $subject),
                'supplements' => $supplementRepository->findByStudentAndSubject($student, $subject),
                'exos' => $exos,
            ];
        }

        $path = $parameterBag->get('kernel.project_dir').'/assets/images/cy_ico.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $cyIcon = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($path));

        $html =  $this->renderView('pdf_generator/index.html.twig', [
            'data' => $data,
            'cyIcon' => $cyIcon,
        ]);

        return new Response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-disposition' => 'attachment; filename=page.html'
        ]);
    }


}
