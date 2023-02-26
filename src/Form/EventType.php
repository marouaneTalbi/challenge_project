<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('lieu')
            ->add('public')
            ->add('event_start')
            ->add('event_end')
            ->add('background_color', ColorType::class)
            ->add('border_color', ColorType::class)
            ->add('text_color', ColorType::class)
            ->add('invite', EntityType::class, [
                'class' => User::class,
                'multiple' => true,
                'choice_label' => 'firstname',
                'attr' => [
                    'class' => 'js-example-basic-multiple'
                ],
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->findByRoleForForm('ROLE_FAN');
                },
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
