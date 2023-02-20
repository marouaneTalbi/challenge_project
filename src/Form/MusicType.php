<?php

namespace App\Form;

use App\Entity\Music;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MusicType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Nom'

            ]
        ])
            ->add('url', TextType::class, [
                'label' => 'Url',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Url'
    
                ]
            ])
            ->add('size', TextType::class, [
                'label' => 'Taille',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Taille'
    
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'attr' => [
                    'class' => 'select'
                ],
                'choices' => [
                    'mp3' => 'MP3',
                    'mp4' => 'MP4',

                ],
           
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Music::class,
        ]);
    }
}
