<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class,[
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('roles', ChoiceType::class, array(
                'multiple' => true,
                'choices' => [
                    'admin' => 'ROLE_ADMIN',
                    'user' => 'ROLE_USER',
                    'client' => 'ROLE_CLIENT',

                ],
                'attr' => [
                    'class' => 'select'
                ]
            ))
            ->add('password',TextType::class,[
                'label' => 'Password',
                'attr' => [
                    'class' => 'form-control'
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
