<?php

namespace App\Controller;

use App\Admin\PaginationService;
use App\Admin\Services;
use App\Entity\ModuleParent;
use App\Form\ModuleParentType;
use App\Repository\ModuleParentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/parent")
 * il s'agit du parent des module
 */
class ParentController extends AbstractController
{
    /**
     * @Route("/{page<\d+>?1}", name="parent")
     */
    public function index(ModuleParentRepository $repository,$page,PaginationService $paginationService): Response
    {

        $paginationService->setEntityClass(ModuleParent::class)
                          ->setPage($page);

        return $this->render('admin/parent/index.html.twig', [
           'pagination'=>$paginationService,
            'tableau'=>['titre'=>'titre','ordre'=>'ordre'],
            'modal' => 'modal',
            'titre' => 'Liste des parents',
        ]);
    }

    /**
     * @Route("/new", name="parent_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface  $em): Response
    {
        $parent = new ModuleParent();
        $form = $this->createForm(ModuleParentType::class,$parent, [
            'method' => 'POST',
            'action' => $this->generateUrl('parent_new')
        ]);
        $form->handleRequest($request);

        $isAjax = $request->isXmlHttpRequest();

        if($form->isSubmitted())
        {
            $response = [];
            $redirect = $this->generateUrl('parent');

           if($form->isValid()){
               $parent->setActive(1);
               $em->persist($parent);
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

        return $this->render('admin/parent/new.html.twig', [
            'parent' => $parent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="parent_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,ModuleParent $parent, EntityManagerInterface  $em): Response
    {

        $form = $this->createForm(ModuleParentType::class,$parent, [
            'method' => 'POST',
            'action' => $this->generateUrl('parent_edit',[
                'id'=>$parent->getId(),
            ])
        ]);
        $form->handleRequest($request);

        $isAjax = $request->isXmlHttpRequest();

        if($form->isSubmitted())
        {

            $response = [];
            $redirect = $this->generateUrl('parent');

            if($form->isValid()){
                $em->persist($parent);
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

        return $this->render('admin/parent/edit.html.twig', [
            'parent' => $parent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="parent_show", methods={"GET"})
     */
    public function show(ModuleParent $parent): Response
    {
        $form = $this->createForm(ModuleParentType::class,$parent, [
            'method' => 'POST',
            'action' => $this->generateUrl('parent_edit',[
                'id'=>$parent->getId(),
            ])
        ]);

        return $this->render('admin/parent/voir.html.twig', [
            'parent' => $parent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/active", name="parent_active", methods={"GET"})
     */
    public function active($id,ModuleParent $parent, SerializerInterface $serializer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();


        if ($parent->getActive() == 1){

            $parent->setActive(0);

        }else{

            $parent->setActive(1);

        }
        $json = $serializer->serialize($parent, 'json', ['groups' => ['normal']]);
        $entityManager->persist($parent);
        $entityManager->flush();
        return $this->json([
            'code'=>200,
            'message'=>'ça marche bien',
            'active'=>$parent->getActive(),
        ],200);


    }


    /**
     * @Route("/delete/{id}", name="parent_delete", methods={"POST","GET","DELETE"})
     */
    public function delete(Request $request, EntityManagerInterface $em,ModuleParent $parent): Response
    {


        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'parent_delete'
                    ,   [
                        'id' => $parent->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->remove($parent);
            $em->flush();

            $redirect = $this->generateUrl('parent');

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
        return $this->render('admin/parent/delete.html.twig', [
            'parent' => $parent,
            'form' => $form->createView(),
        ]);
    }

}
