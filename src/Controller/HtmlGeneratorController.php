<?php

namespace App\Controller;

use App\Entity\Student;
use App\Generator\HtmlGenerator;
use App\Repository\StudentRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[Route('/html/generator')]
class HtmlGeneratorController extends AbstractController
{
    #[Route('/all', name: 'app_html_generator_create', methods: ['GET'])]
    public function createAll(
        StudentRepository $studentRepository, 
        HtmlGenerator $htmlGenerator,
        ParameterBagInterface $parameterBag
    ): Response {
        $students = $studentRepository->findAll();
        $filesystem = new Filesystem();

        foreach ($students as $student) {
            $filesystem->dumpFile($parameterBag->get('srore_dir_absolute').'/'.$student->getName().'.html', $htmlGenerator->generate($student));
        }

        $this->addFlash(
            'success',
            'Tout les notes des étudiants ont été générer'
        );

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_html_generator', methods: ['GET'])]
    public function index(
        Student $student,
        HtmlGenerator $htmlGenerator
    ): Response {
        return new Response($htmlGenerator->generate($student), 200, [
            'Content-Type' => 'text/html',
            'Content-disposition' => 'attachment; filename=page.html'
        ]);
    }
}
