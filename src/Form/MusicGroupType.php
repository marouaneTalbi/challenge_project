<?php

namespace App\Form;

use App\Entity\MusicGroup;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class MusicGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'nom du groupe',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Firstname'

                ]
            ])
            ->add('artiste', EntityType::class, [
                'class' => User::class,
                'multiple' => true,
                'choice_label' => 'firstname',
                'attr' => [
                    'class' => 'js-example-basic-multiple'
                ],
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->findByRoleForForm('ROLE_ARTIST');
                },
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MusicGroup::class,
        ]);
    }
}
