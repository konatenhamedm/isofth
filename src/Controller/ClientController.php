<?php

namespace App\Controller;

use App\Admin\PaginationService;
use App\Entity\Client ;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/admin")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/client/{page<\d+>?1}", name="client")
     */
    public function index(ClientRepository $repository,$page,PaginationService $paginationService): Response
    {

        $paginationService->setEntityClass(Client ::class)
            ->setPage($page);

        return $this->render('admin/client/index.html.twig', [
            'pagination'=>$paginationService,
            'tableau'=>['nom'=>'nom','contact'=>'contact','email'=>'email'],
            'modal' => ' ',
            'titre' => 'Liste des clients',
        ]);
    }

    /**
     * @Route("/client/new", name="client_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface  $em): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class,$client, [
            'method' => 'POST',
            'action' => $this->generateUrl('client_new')
        ]);
        $form->handleRequest($request);

        $isAjax = $request->isXmlHttpRequest();

        if($form->isSubmitted())
        {
            $response = [];
            $redirect = $this->generateUrl('client');

            if($form->isValid()){
                $client->setActive(1);
                $em->persist($client);
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

        return $this->render('admin/client/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/client/{id}/edit", name="client_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,Client $client, EntityManagerInterface  $em): Response
    {

        $form = $this->createForm(ClientType::class,$client, [
            'method' => 'POST',
            'action' => $this->generateUrl('client_edit',[
                'id'=>$client->getId(),
            ])
        ]);
        $form->handleRequest($request);

        $isAjax = $request->isXmlHttpRequest();

        if($form->isSubmitted())
        {

            $response = [];
            $redirect = $this->generateUrl('client');

            if($form->isValid()){
                $em->persist($client);
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

        return $this->render('admin/client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/client/{id}/show", name="client_show", methods={"GET"})
     */
    public function show(Client $client): Response
    {
        $form = $this->createForm(ClientType::class,$client, [
            'method' => 'POST',
            'action' => $this->generateUrl('client_edit',[
                'id'=>$client->getId(),
            ])
        ]);

        return $this->render('admin/client/voir.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/client/{id}/active", name="client_active", methods={"GET"})
     */
    public function active($id,Client $client, SerializerInterface $serializer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();


        if ($client->getActive() == 1){

            $client->setActive(0);

        }else{

            $client->setActive(1);

        }
        $json = $serializer->serialize($client, 'json', ['groups' => ['normal']]);
        $entityManager->persist($client);
        $entityManager->flush();
        return $this->json([
            'code'=>200,
            'message'=>'ça marche bien',
            'active'=>$client->getActive(),
        ],200);


    }


    /**
     * @Route("/client/delete/{id}", name="client_delete", methods={"POST","GET","DELETE"})
     */
    public function delete(Request $request, EntityManagerInterface $em,Client $client): Response
    {


        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'client_delete'
                    ,   [
                        'id' => $client->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->remove($client);
            $em->flush();

            $redirect = $this->generateUrl('client');

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
        return $this->render('admin/client/delete.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }
}
