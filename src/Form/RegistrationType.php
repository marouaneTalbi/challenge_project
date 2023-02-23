<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,[
                'label' => 'Firstname',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Firstname',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your Firstname should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Firstname'

                ]
            ])

            ->add('lastname', TextType::class,[
                'label' => 'Lastname',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Lastname',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your Lastname should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Lastname'
                ]
            ])
            ->add('email', EmailType::class,[
                'help' => 'you will receive a confirmation email',
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email'

                ]
            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'first_options'  => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 4,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            'max' => 4096,
                        ]),


                    ],
                    'label' => 'Password'
                ],
                'second_options' => ['label' => 'Repeat Password'],
                'options' => [ 
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Password'
                    ],
                ],
            ]);
            if ($options['is_artist'] || $options['is_manager']) {
                $builder
                    ->add('description', TextareaType::class, [
                        'help' => 'Ce champ vous permet de décrire votre parcours dans le milieu musical ou en tant que manager.',
                        'label' => 'Description',
                        'required' => false,
                        'attr' => ['rows' => 10],
                        'attr' => [
                            'class' => 'form-control'
                        ]
                    ])
                    ->add('promotionLink', UrlType::class, [
                        'help' => 'Ce champ est destiné à vous permettre de vous promouvoir en partageant un lien vers votre site web ou une page spécifique',
                        'label' => 'Promotion',
                        'attr' => [
                            'class' => 'form-control'
                        ]
                    ])
                ;
            }
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_artist' => false,
            'is_manager' => false,
        ]);
    }
}
