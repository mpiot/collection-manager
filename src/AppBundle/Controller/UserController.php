<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ChangePasswordType;
use AppBundle\Form\Type\ProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/my-profile", name="user_profile")
     */
    public function profileAction()
    {
        $user = $this->getUser();

        return $this->render('user/profile/view.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/my-profile/edit", name="user_profile_edit")
     */
    public function profileEditAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Your profile have been successfully edited.');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/my-profile/password/edit", name="user_password_edit")
     */
    public function editPasswordAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Your password have been successfully changed.');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/password/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
