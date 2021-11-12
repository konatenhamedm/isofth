<?php

namespace App\Admin;

use App\Entity\Groupe;
use App\Entity\Parametre;
use App\Entity\Service;
use App\Entity\Icon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as container;
use Symfony\Component\HttpFoundation\RequestStack;

class Services
{

    private $em;
    private $route;
    private $container;

    public function __construct(EntityManagerInterface $em, RequestStack $route, Container $container)
    {
        $this->em = $em;
        $this->route = $route->getCurrentRequest()->attributes->get('_route');
        $this->container = $container->get('router')->getRouteCollection()->all();
//dd($this->container);
    }

    public function getRoute()
    {

        return $this->route;
    }

    public function listeModule()
    {
        $repo = $this->em->getRepository(Groupe::class)->afficheModule();

        return $repo;
    }

    public function listeServices()
    {
        $repo = $this->em->getRepository(Service::class)->findService();
        return $repo;
    }
    public function findParametre()
    {
        $repo = $this->em->getRepository(Parametre::class)->findParametre();
        return $repo;
    }

    public function liste()
    {
        $repo = $this->em->getRepository(Groupe::class)->afficheGroupes();

        return $repo;
    }

    public function listeIcon($val)
    {
        $repo = $this->em->getRepository(Icon::class)->findIcon($val);
        return $repo;
    }

    public function listeParent()
    {
        $repo = $this->em->getRepository(Groupe::class)->affiche();

        return $repo;
    }

    public function listeLien()
    {
        $array = [
            'utilisateur'=>'utilisateur',
            'module'=>'module',
            'service'=>'service',
            'groupe'=>'groupe',
            'parent'=>'parent',
            'parametre'=>'parametre',
            'client'=>'client',
            'abonnement'=>'abonnement',
        ];
     /*   foreach ($this->container as $el=> $params) {
          $resultat=  $params->getDefaults();
           /// dd($params);
            if (stripos($params, '_') !== FALSE) {
                array_push( $array, $params);
            }
        }*/
        return $array ;
    }

}