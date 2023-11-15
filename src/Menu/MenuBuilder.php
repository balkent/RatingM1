<?php

namespace App\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(): ItemInterface
    {
        $links = [
            'Accueil' => 'app_home',
            'Notes' => 'app_score_index',
            'Eleves' => 'app_student_index',
            'Sujets' => 'app_subject_index',
            'Suppléments' => 'app_supplement_index',
            'Suppléments Type' => 'app_supplement_type_index',
            'Exo' => 'app_exercise_index',
            'Reponses' => 'app_answer_index',
            'Schema' => 'app_home_schema',
        ];

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav');

        foreach ($links as $label => $route) {
            $menu->addChild($label, [
                'route' => $route,
                'attributes' => ['class' => 'nav-item']
            ]);
            $menu[$label]->setLinkAttribute('class', 'nav-link');
        }

        return $menu;
    }
}
