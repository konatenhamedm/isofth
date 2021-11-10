<?php

namespace App\Controller;

use App\Admin\PaginationService;
use App\Entity\Abonnement ;
use App\Form\AbonnementType;
use App\Repository\AbonnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/admin")
 */
class AbonnementController extends AbstractController
{
    /**
     * @Route("/abonnement/{page<\d+>?1}", name="abonnement")
     */
    public function index(AbonnementRepository $repository,$page,PaginationService $paginationService): Response
    {

        $paginationService->setEntityClass(Abonnement ::class)
            ->setPage($page);

        return $this->render('admin/abonnement/index.html.twig', [
            'pagination'=>$paginationService,
            'tableau'=>[
                'client'=>'client',
                'dateDebut'=>'date debut',
                'dateFin'=>'date fin',
                'etat'=>'etat',
                ],
            'modal' => 'modal',
            'titre' => 'Liste des abonnements',
        ]);
    }

    /**
     * @Route("/abonnement/new", name="abonnement_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface  $em): Response
    {
        $abonnement = new Abonnement();
        $form = $this->createForm(AbonnementType::class,$abonnement, [
            'method' => 'POST',
            'action' => $this->generateUrl('abonnement_new')
        ]);
        $form->handleRequest($request);

        $isAjax = $request->isXmlHttpRequest();

        if($form->isSubmitted())
        {
            $response = [];
            $redirect = $this->generateUrl('abonnement');

            if($form->isValid()){
                $abonnement->setActive(1);
                $em->persist($abonnement);
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

        return $this->render('admin/abonnement/new.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/abonnement/{id}/edit", name="abonnement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,Abonnement $abonnement, EntityManagerInterface  $em): Response
    {

        $form = $this->createForm(AbonnementType::class,$abonnement, [
            'method' => 'POST',
            'action' => $this->generateUrl('abonnement_edit',[
                'id'=>$abonnement->getId(),
            ])
        ]);
        $form->handleRequest($request);

        $isAjax = $request->isXmlHttpRequest();

        if($form->isSubmitted())
        {

            $response = [];
            $redirect = $this->generateUrl('abonnement');

            if($form->isValid()){
                $em->persist($abonnement);
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

        return $this->render('admin/abonnement/edit.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/abonnement/{id}/show", name="abonnement_show", methods={"GET"})
     */
    public function show(Abonnement $abonnement): Response
    {
        $form = $this->createForm(AbonnementType::class,$abonnement, [
            'method' => 'POST',
            'action' => $this->generateUrl('abonnement_edit',[
                'id'=>$abonnement->getId(),
            ])
        ]);

        return $this->render('admin/abonnement/voir.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/abonnement/{id}/active", name="abonnement_active", methods={"GET"})
     */
    public function active($id,Abonnement $abonnement, SerializerInterface $serializer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();


        if ($abonnement->getActive() == 1){

            $abonnement->setActive(0);

        }else{

            $abonnement->setActive(1);

        }
        $json = $serializer->serialize($abonnement, 'json', ['groups' => ['normal']]);
        $entityManager->persist($abonnement);
        $entityManager->flush();
        return $this->json([
            'code'=>200,
            'message'=>'ça marche bien',
            'active'=>$abonnement->getActive(),
        ],200);


    }


    /**
     * @Route("/abonnement/delete/{id}", name="abonnement_delete", methods={"POST","GET","DELETE"})
     */
    public function delete(Request $request, EntityManagerInterface $em,Abonnement $abonnement): Response
    {


        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'abonnement_delete'
                    ,   [
                        'id' => $abonnement->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->remove($abonnement);
            $em->flush();

            $redirect = $this->generateUrl('abonnement');

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
        return $this->render('admin/abonnement/delete.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form->createView(),
        ]);
    }
}
