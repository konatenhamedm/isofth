<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MasterController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ServiceRepository $repository): Response
    {
        $liste= $repository->findService();
       // date_date_set($liste);
        return $this->render('fils/_includes/index.html.twig', [
          'service'=> $liste,
        ]);
    }
}
