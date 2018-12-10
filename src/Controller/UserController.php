<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users")
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas l'accès à ces fonctions")
     */
    public function list()
    {
        return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository(User::class)->findAll()]);
    }

    /**
     * @Route("/users/create")
     */
    public function create(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['user' => $user]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('app_user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit")
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas l'accès à ces fonctions")
     */
    public function edit(User $user, Request $request, UserService $userService)
    {
        $form = $this->createForm(UserType::class, $user, array('user' => $user));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userService->editUser($user,$form);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('app_user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    /**
     * @Route("/profile")
     * @IsGranted("ROLE_USER")
     */
    public function view()
    {
        $user = $this->getUser();

        return $this->render('user/view.html.twig', ['user' => $user]);
    }
}
