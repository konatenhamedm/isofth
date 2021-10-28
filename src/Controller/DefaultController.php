<?php

namespace App\Controller;

use App\Admin\Services;
use App\Repository\ModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/dashboard", name="default")
     */
    public function index(Services $services,ModuleRepository $repository): Response
    {
        //dd($repository->findAll());
        return $this->render('admin/includes/_index.html.twig', [
           'couleur'=>'#081217'
        ]);
    }
}
