<?php

namespace App\Controller;



use App\Repository\ModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/admin", name="default")
     */
    public function index(Services $services,ModuleRepository $repository): Response
    {
        //dd($repository->findAll());
        return $this->render('admin/includes/_index.html.twig', [
           'couleur'=>'#081217'
        ]);
    }

    /**
     * @Route("/data",name="data")
     */
    public function datatable():Response
    {
        return $this->render('datatable.html.twig');
    }


}
