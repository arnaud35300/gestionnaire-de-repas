<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\UserAddType;
use App\Service\Uploader;
use App\Form\UserEditType;
use App\Form\ChangePasswordFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user", name="user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/add", name="_add", methods={"GET","POST"})
     */
    public function add(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        LoginFormAuthenticator $authenticator,
        GuardAuthenticatorHandler $guardHandler
    ) {
        $user = new User();

        $form = $this->createForm(UserAddType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $encoder->encodePassword($user, $user->getPassword())
            );

            $manager = $this->getDoctrine()->getManager();

            //TODO make events listener
            $role = $this->getDoctrine()->getRepository(Role::class)->findOneByName('ROLE_USER');
            $user->setCreatedAt(new \DateTime());
            $user->setRole($role);
            //TODO

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Welcome ' . $user->getName());

            // login user
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'home'
            );
        }

        return $this->render(
            'user/add.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/profile", name="_profile", methods={"GET"})
     */
    public function profile(Request $request)
    {
        return $this->render(
            'user/profile.html.twig',
            [
                'user' => $this->getUser()
            ]
        );
    }

    /**
     * @Route("/edit", name="_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Uploader $uploader)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

            $file = $form['image']->getData();
            //TODO make service
            if ($file !== null) {

                $path = $this->getParameter('kernel.project_dir') . '/public/data/user_profile_pictures';

                $errors = $uploader->upload($file, $path, $user);

                if (count($errors) > 0) {

                    foreach ($errors as $error)
                        $this->addFlash('error', $error);

                    return $this->redirectToRoute('user_edit');
                }
            }

            //TODO make events listener
            $user->setUpdatedAt(new \DateTime());
            //TODO

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Your informations has been updated ');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user
            ]
        );
    }

    /**
     * @Route("/password", name="_edit_password", methods={"GET","POST"})
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class, null, ['reset_password' => true]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

            $user->setPassword(
                $encoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            )->setUpdatedAt(new \DateTime());

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Your password has been changed');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render(
            'user/edit_password.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user
            ]
        );
    }

    /**
     * @Route("/delete", name="_delete", methods={"DELETE"})
     */
    public function delete(Request $request)
    {
        // disconnect current user
        $session = new Session();
        $session->invalidate();

        if ($this->isCsrfTokenValid('delete' . $this->getUser()->getId(), $request->request->get('_token'))) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($this->getUser());
            $manager->flush();
        }

        $this->addFlash('delete', 'User account deleted');

        return $this->redirectToRoute('login');
    }
}
