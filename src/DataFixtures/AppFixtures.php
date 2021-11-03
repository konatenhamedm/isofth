<?php

namespace App\DataFixtures;

use App\Entity\ModuleParent;
use App\Entity\User;
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

        $user = new User();

        $user->setName('konate')
             ->setemail('konatenhamed@gmail.com')
             ->setPassword('$2y$13$qo4/UPpc/bBO5ru6zXxnFuDwJxxnf5x1BbqvX5ugyLodW9rzqSY2S');

        $manager->persist($parent);
        $manager->persist($user);
        $manager->flush();
    }
}
