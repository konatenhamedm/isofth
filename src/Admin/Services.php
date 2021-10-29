<?php

namespace App\Admin;

use App\Entity\Groupe;
use App\Entity\Service;
use App\Entity\Icon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Services{

    private $em;
    private $route;

    public function __construct(EntityManagerInterface $em,RequestStack $route)
    {
        $this->em = $em;
        $this->route = $route->getCurrentRequest()->attributes->get('_route');

    }
    public function getRoute(){

        return $this->route;
    }
    public  function  listeModule(){
        $repo = $this->em->getRepository(Groupe::class)->afficheModule();

        return $repo;
    }
    public  function  listeServices(){
        $repo = $this->em->getRepository(Service::class)->findService();
        return $repo;
    }
    public  function  liste(){
        $repo = $this->em->getRepository(Groupe::class)->afficheGroupes();

        return $repo;
    }
    public  function  listeIcon($val){
        $repo = $this->em->getRepository(Icon::class)->findIcon($val);
        return $repo;
    }
    public  function  listeParent(){
        $repo = $this->em->getRepository(Groupe::class)->affiche();

        return $repo;
    }

}