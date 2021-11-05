<?php

namespace App\Controller;

use App\Admin\PaginationService;
use App\Admin\UploaderHelper;
use App\Entity\Parametre ;
use App\Form\ParametreType;
use App\Repository\ParametreRepository ;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/admin")
 */
class ParametreController extends AbstractController
{
    /**
     * @Route("/parametre/{page<\d+>?1}", name="parametre")
     */
    public function index(ParametreRepository $repository,$page,PaginationService $paginationService): Response
    {
        $paginationService->setEntityClass(Parametre ::class)
            ->setPage($page);

        return $this->render('admin/parametre/index.html.twig', [
            'pagination'=>$paginationService,
            'tableau'=>['Logo','Titre'],
            'modal' => 'modal',
            'titre' => 'Liste des parametres',

        ]);
    }

    /**
     * @Route("/new", name="parametre_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface  $em,UploaderHelper  $uploaderHelper): Response
    {
        $parametre = new Parametre ();
        $form = $this->createForm(ParametreType::class,$parametre, [
            'method' => 'POST',
            'action' => $this->generateUrl('parametre_new')
        ]);
        $form->handleRequest($request);

        $isAjax = $request->isXmlHttpRequest();

        if($form->isSubmitted())
        {
            $response = [];
            $redirect = $this->generateUrl('parametre');

            if($form->isValid()){

                $brochureFile = $form->get('logo')->getData();
                $uploadedFile = $form['logo']->getData();

                if ($uploadedFile) {
                    $newFilename = $uploaderHelper->uploadImage($uploadedFile);
                    $parametre->setLogo($newFilename);
                }
                $parametre->setActive(1);
                $em->persist($parametre);
                $em->flush();

                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);

            }
            if ($isAjax) {
                return $this->json( compact('statut', 'message', 'redirect'));
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect);
                }
            }
        }

        return $this->render('admin/parametre/new.html.twig', [
            'parametre' => $parametre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="parametre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,Parametre $parametre, EntityManagerInterface  $em,UploaderHelper  $uploaderHelper): Response
    {

        $form = $this->createForm(ParametreType::class,$parametre, [
            'method' => 'POST',
            'action' => $this->generateUrl('parametre_edit',[
                'id'=>$parametre->getId(),
            ])
        ]);
        $form->handleRequest($request);

        $isAjax = $request->isXmlHttpRequest();

        if($form->isSubmitted())
        {

            $response = [];
            $redirect = $this->generateUrl('parametre');

            if($form->isValid()){

                $brochureFile = $form->get('logo')->getData();
                $uploadedFile = $form['logo']->getData();

                if ($uploadedFile) {
                    $newFilename = $uploaderHelper->uploadImage($uploadedFile);
                    $parametre->setLogo($newFilename);
                }

                $em->persist($parametre);
                $em->flush();

                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);

            }

            if ($isAjax) {
                return $this->json( compact('statut', 'message', 'redirect'));
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect);
                }
            }
        }

        return $this->render('admin/parametre/edit.html.twig', [
            'parametre' => $parametre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="parametre_show", methods={"GET"})
     */
    public function show(Parametre $parametre): Response
    {
        $form = $this->createForm(ParametreType::class,$parametre, [
            'method' => 'POST',
            'action' => $this->generateUrl('parametre_edit',[
                'id'=>$parametre->getId(),
            ])
        ]);

        return $this->render('admin/parametre/voir.html.twig', [
            'parametre' => $parametre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/active", name="parametre_active", methods={"GET"})
     */
    public function active($id,Parametre $parametre, SerializerInterface $serializer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();


        if ($parametre->getActive() == 1){

            $parametre->setActive(0);

        }else{

            $parametre->setActive(1);

        }
        $json = $serializer->serialize($parametre, 'json', ['groups' => ['normal']]);
        $entityManager->persist($parametre);
        $entityManager->flush();
        return $this->json([
            'code'=>200,
            'message'=>'ça marche bien',
            'active'=>$parametre->getActive(),
        ],200);


    }


    /**
     * @Route("/delete/{id}", name="parametre_delete", methods={"POST","GET","DELETE"})
     */
    public function delete(Request $request, EntityManagerInterface $em,Parametre $parametre): Response
    {


        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'parametre_delete'
                    ,   [
                        'id' => $parametre->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->remove($parametre);
            $em->flush();

            $redirect = $this->generateUrl('parametre');

            $message = 'Opération effectuée avec succès';

            $response = [
                'statut'   => 1,
                'message'  => $message,
                'redirect' => $redirect,
            ];

            $this->addFlash('success', $message);

            if (!$request->isXmlHttpRequest()) {
                return $this->redirect($redirect);
            } else {
                return $this->json($response);
            }



        }
        return $this->render('admin/parametre/delete.html.twig', [
            'parametre' => $parametre,
            'form' => $form->createView(),
        ]);
    }

}
