<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Service;
use App\Form\ModuleType;
use App\Repository\ImagesRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MasterController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index( ): Response
    {

        return $this->render('fils/_includes/index.html.twig', [

        ]);
    }

    /**
     * @Route("service-details/{id}/", name="service_detail", methods={"GET","POST"})
     */
    public function detailsService(Request $request,ImagesRepository $repository,$id,Service $service): Response
    {
        $image = $repository->findImages($id);

        return $this->render('fils/_includes/service_details.html.twig', [
           'images'=>$image,
            'id'=>$id,
            'service'=>$service
        ]);
    }
}
