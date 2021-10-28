<?php

namespace App\Controller;

use App\Admin\PaginationService;
use App\Admin\UploaderHelper;
use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/service")
 */
class ServiceController extends AbstractController
{
    /**
     * @Route("/{page<\d+>?1}", name="service")
     */
    public function index(serviceRepository $repository, $page, PaginationService $paginationService): Response
    {
        $paginationService->setEntityClass(Service::class)
            ->setPage($page);

        return $this->render('admin/service/index.html.twig', [
            'pagination' => $paginationService,
            'tableau' => ['image'=> 'image','titre'=> 'titre'],
            'modal' => ' ',
            'titre' => 'Liste des services',

        ]);
    }

    /**
     * @Route("/new", name="service_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface  $em,UploaderHelper  $uploaderHelper): Response
    {
        $service = new Service();
        $form = $this->createForm(serviceType::class, $service, [
            'method' => 'POST',
            'action' => $this->generateUrl('service_new')
        ]);
        $form->handleRequest($request);

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted() && $form->isValid()) {
            $response = [];
            $redirect = $this->generateUrl('service');

                /** @var UploadedFile $brochureFile */
            $brochureFile  = $form->get('images')->getData(); //get('image_prod')->getData();

                foreach ($brochureFile  as $image) {

                    $file = new File($image->getPath());
                    $newFilename = md5(uniqid()) . '.' . $file->guessExtension();
                    // $fileName = md5(uniqid()).'.'.$file->guessExtension();
                    $file->move($this->getParameter('images_directory'), $image->getPath());
                    $image->setPath($newFilename);
                   /* $em = $this->getDoctrine()->getManager();
                    $em->persist($image);
                    $em->flush();*/
                }

                //$brochureFile = $form->get('image')->getData();
                $uploadedFile = $form['image']->getData();

                if ($uploadedFile) {
                    $newFilename = $uploaderHelper->uploadImage($uploadedFile);
                    $service->setImage($newFilename);
                }

                $service->setActive(1);
                $em->persist($service);
                $em->flush();

                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);

            if ($isAjax) {
                return $this->json(compact('statut', 'message', 'redirect'));
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect);
                }
            }
        }

        return $this->render('admin/service/new.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="service_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Service $service, EntityManagerInterface  $em): Response
    {

        $form = $this->createForm(serviceType::class, $service, [
            'method' => 'POST',
            'action' => $this->generateUrl('service_edit', [
                'id' => $service->getId(),
            ])
        ]);
        $form->handleRequest($request);

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('service');

            if ($form->isValid()) {
                $em->persist($service);
                $em->flush();

                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
            }

            if ($isAjax) {
                return $this->json(compact('statut', 'message', 'redirect'));
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect);
                }
            }
        }

        return $this->render('admin/service/edit.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="service_show", methods={"GET"})
     */
    public function show(Service $service): Response
    {
        $form = $this->createForm(serviceType::class, $service, [
            'method' => 'POST',
            'action' => $this->generateUrl('service_edit', [
                'id' => $service->getId(),
            ])
        ]);

        return $this->render('admin/service/voir.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/active", name="service_active", methods={"GET"})
     */
    public function active($id, Service $service, SerializerInterface $serializer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();


        if ($service->getActive() == 1) {

            $service->setActive(0);
        } else {

            $service->setActive(1);
        }
        $json = $serializer->serialize($service, 'json', ['groups' => ['normal']]);
        $entityManager->persist($service);
        $entityManager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'ça marche bien',
            'active' => $service->getActive(),
        ], 200);
    }


    /**
     * @Route("/delete/{id}", name="service_delete", methods={"POST","GET","DELETE"})
     */
    public function delete(Request $request, EntityManagerInterface $em, Service $service): Response
    {


        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'service_delete',
                    [
                        'id' => $service->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->remove($service);
            $em->flush();

            $redirect = $this->generateUrl('service');

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
        return $this->render('admin/service/delete.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }
}
