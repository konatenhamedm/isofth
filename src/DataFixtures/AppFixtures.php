<?php

namespace App\DataFixtures;

use App\Entity\ModuleParent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $parent = new ModuleParent();
        $parent->setTitre('PARAMETRAGES');
        $parent->setOrdre(2);
        $parent->setActive(1);
        $manager->persist($parent);
        $manager->flush();
    }
}
