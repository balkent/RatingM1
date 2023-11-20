<?php

namespace App\Generator;

use Twig\Environment;
use App\Entity\Student;
use App\Repository\StudentRepository;
use App\Repository\SubjectRepository;
use App\Repository\ExerciseRepository;
use App\Generator\ImageBase64Generator;
use App\Repository\SupplementRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class HtmlGenerator
{
    public function __construct(
        private SubjectRepository $subjectRepository,
        private StudentRepository $studentRepository,
        private ExerciseRepository $exerciseRepository,
        private SupplementRepository $supplementRepository,
        private ImageBase64Generator $imageBase64Generator,
        private ParameterBagInterface $parameterBag,
        private Environment $templating
    ) {
    }

    public function generate(Student $student, string $color = 'light'): string
    {
        $data = [];        
        $subjects = $this->subjectRepository->findAll();

        $data['student'] = $student;
        $data['score'] = $this->studentRepository->globalScore($student);
        foreach ($subjects as $subject) {
            $exos = $this->exerciseRepository->findBy(['subject' => $subject]);
            foreach ($exos as $exo) {
                if (null !== $picture = $exo->getPicture()) {
                    $exo->setPictureBase64($this->imageBase64Generator->generate('exo/'.$picture));
                }
            }
            $data['subjects'][$subject->getLibelle()] = [
                'score' => $this->studentRepository->scoreByStudentAndSubject($student, $subject),
                'supplements' => $this->supplementRepository->findByStudentAndSubject($student, $subject),
                'exos' => $exos,
            ];
        }

        $path = $this->parameterBag->get('kernel.project_dir').'/assets/images/cy_ico.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $cyIcon = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($path));

        return $this->templating->render('pdf_generator/index.html.twig', [
            'color' => $color,
            'data' => $data,
            'cyIcon' => $cyIcon,
        ]);
    }
}
