<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'placeholder' => 'enter secure password'
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 50,
                            'maxMessage' => 'Your password must be shorter than {{ limit }} characters'
                        ]),
                    ],
                    'label' => 'New password',
                ],
                'second_options' => [
                    'attr' => [
                        'placeholder' => 'repeat'
                    ],
                    'label' => 'Repeat Password',
                ],
                'invalid_message' => 'The password fields must match.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;

        if ($options['reset_password'])
        {
            $builder->add('password', PasswordType::class, [
                'attr' => [
                    'placeholder' => 'your last password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password'
                    ]),
                    new UserPassword([
                        'message' => 'Wrong value for your current password'
                    ])
                ]
            ]);
        } 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // set true to add last password field
            'reset_password' => false
        ]);
    }
}
