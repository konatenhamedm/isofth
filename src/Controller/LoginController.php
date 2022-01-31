<?php

namespace App\Controller;



use App\Admin\MailerService;
use App\Admin\PaginationService;
use App\Admin\UploaderHelper;
use App\Entity\Service;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\ModuleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/admin")
 */
class LoginController extends AbstractController
{
    /**
     * @Route("/utilisateur/admin", name="default")
     */
    public function admin(Services $services,ModuleRepository $repository): Response
    {
        //dd($repository->findAll());
        return $this->render('admin/includes/_index.html.twig', [
           'couleur'=>'#081217'
        ]);
    }

    /**
     * @Route("/utilisateur/{page<\d+>?1}", name="utilisateur")
     */
    public function index(UserRepository $repository, $page, PaginationService $paginationService): Response
    {
        $paginationService->setEntityClass(User::class)
            ->setPage($page);

        return $this->render('admin/utilisateur/index.html.twig', [
            'pagination' => $paginationService,
            'tableau' => ['nom'=> 'Nom','email'=> 'Email'],
            'modal' => 'modal',
            'titre' => 'Liste des utilisateurs',

        ]);
    }

    /**
     * @Route("/utilisateur/new", name="utilisateur_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface  $em, MailerService  $mailerService,UploaderHelper  $uploaderHelper,UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $utilisateur = new User();
        $form = $this->createForm(UserType::class, $utilisateur, [
            'method' => 'POST',
            'action' => $this->generateUrl('utilisateur_new')
        ]);
        $form->handleRequest($request);

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted() && $form->isValid()) {
            $response = [];
            $redirect = $this->generateUrl('utilisateur');

            $hashedPassword = $userPasswordHasher->hashPassword(
                $utilisateur,
                $form['plainPassword']->getData()
            );
            $utilisateur->setPassword($hashedPassword);

            $utilisateur->setActive(1);
            $mailerService->send(
                'ESSAI ENVOI',
                "konatenhamed@gmail.com",
                $form['email']->getData(),
                "admin/mail.html.twig",
                [
                    'message' => 'bien recu',
                    'email' => $form['email']->getData(),
                    'nom' => $form['name']->getData(),
                ]
            );
            $em->persist($utilisateur);
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

        return $this->render('admin/utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/utilisateur/{id}/edit", name="utilisateur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $utilisateur, EntityManagerInterface  $em,UploaderHelper  $uploaderHelper): Response
    {

        $form = $this->createForm(UserType::class, $utilisateur, [
            'method' => 'POST',
            'action' => $this->generateUrl('utilisateur_edit', [
                'id' => $utilisateur->getId(),
            ])
        ]);
        $form->handleRequest($request);

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('utilisateur');

            if ($form->isValid()) {

                $em->persist($utilisateur);
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

        return $this->render('admin/utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/utilisateur/{id}/show", name="utilisateur_show", methods={"GET"})
     */
    public function show(Service $utilisateur): Response
    {
        $form = $this->createForm(utilisateurType::class, $utilisateur, [
            'method' => 'POST',
            'action' => $this->generateUrl('utilisateur_edit', [
                'id' => $utilisateur->getId(),
            ])
        ]);

        return $this->render('admin/utilisateur/voir.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/utilisateur/{id}/active", name="utilisateur_active", methods={"GET"})
     */
    public function active($id, User $utilisateur, SerializerInterface $serializer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();


        if ($utilisateur->getActive() == 1) {

            $utilisateur->setActive(0);
        } else {

            $utilisateur->setActive(1);
        }
        $json = $serializer->serialize($utilisateur, 'json', ['groups' => ['normal']]);
        $entityManager->persist($utilisateur);
        $entityManager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'ça marche bien',
            'active' => $utilisateur->getActive(),
        ], 200);
    }


    /**
     * @Route("/utilisateur/delete/{id}", name="utilisateur_delete", methods={"POST","GET","DELETE"})
     */
    public function delete(Request $request, EntityManagerInterface $em, User $utilisateur): Response
    {


        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'utilisateur_delete',
                    [
                        'id' => $utilisateur->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->remove($utilisateur);
            $em->flush();

            $redirect = $this->generateUrl('utilisateur');

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
        return $this->render('admin/utilisateur/delete.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }
}
