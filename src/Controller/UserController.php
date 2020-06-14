<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\UserAddType;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

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
        GuardAuthenticatorHandler $guardHandler)
    {
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

        return $this->render('user/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
