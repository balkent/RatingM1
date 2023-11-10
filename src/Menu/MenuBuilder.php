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

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Accueil', ['route' => 'app_home']);
        $menu->addChild('Notes', ['route' => 'app_score_index']);
        $menu->addChild('Eleves', ['route' => 'app_student_index']);
        $menu->addChild('Suppléments', ['route' => 'app_supplement_index']);

        return $menu;
    }
}