<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\UserAddType;
use App\Form\UserEditType;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
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
    public function edit(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

            $file = $form['image']->getData();

            //TODO make service
            if ($file !== null) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/data/user_profile_pictures';
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($destination, $filename);
                
                if ($user->getPath() !== 'user_profile.svg')
                    unlink($destination . '/' . $user->getPath());

                $user->setPath($filename);
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
}
