<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;


class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('image', FileType::class,array('data_class' => null))

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
            ->add('email', TextType::class,[
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email'
                ]
            ])
           
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
